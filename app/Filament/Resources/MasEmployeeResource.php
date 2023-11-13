<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasEmployeeResource\Pages;
use App\Filament\Resources\MasEmployeeResource\RelationManagers;
use App\Models\MasEmployee;
use App\Models\Region;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Facades\Hash;


class MasEmployeeResource extends Resource
{
    protected static ?string $model = MasEmployee::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Employee-Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                ->required()
                ->maxLength(100),
                Forms\Components\TextInput::make('middle_name')
                    ->maxLength(100),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(100),
                Forms\Components\TextInput::make('emp_id')
                ->required()
                ->maxLength(50)->label("Emp Id"),
                Forms\Components\TextInput::make('email')
                ->required()
                ->maxLength(100),
                Forms\Components\Select::make('grade_id')
                    ->relationship('grade', 'name')
                    ->required()->reactive(),
                Forms\Components\Select::make('grade_step_id')
                ->relationship('gradeStep', 'name', fn (Builder $query, callable $get) => $query->whereRaw("grade_id = ?",[$get('grade_id')]))
                ->required()->label("Step"),
                Forms\Components\TextInput::make('created_by')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('edited_by')
                    ->maxLength(36),
                Forms\Components\Select::make('designation_id')
                    ->relationship('designation', 'name')
                    ->required(),
                Forms\Components\Select::make('department_id')
                ->relationship('department', 'name')
                    ->required()->reactive(),
                Forms\Components\Select::make('section_id')
                ->relationship('section', 'name', fn (Builder $query, callable $get) => $query->whereRaw("department_id = ?",[$get('department_id')]))
                ->required()->label('Section'),
                Forms\Components\Select::make('region_id')
                ->options(
                    Region::all()->pluck('name', 'id')->toArray()
                )
                ->required(),
                Forms\Components\DatePicker::make('date_of_appointment')
                    ->required(),
                Forms\Components\Select::make('gender')->options([
                    'M'=> "Male",
                    'F'=> "Female"
                ])->required(),
                Forms\Components\Select::make('employment_type')->options([
                    'regular_period'=> "Regular",
                    'probation_period'=> "Probation",
                    'contract_period' => "Contract",
                ])->required(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrateStateUsing(static function (null|string $state) {
                        return filled($state) ? Hash::make($state) : null;
                    })
                    ->required(static function ($livewire) {
                        // Check for the specific Page instance
                        return $livewire instanceof App\Filament\Resources\MasEmployeeResource\Pages\CreateUser;
                    })
                    ->dehydrated(static function (null|string $state) {
                        return filled($state);
                    })
                    ->label(static function ($livewire) {
                        // Customize the label based on the specific Page instance
                        return $livewire instanceof App\Filament\Resources\MasEmployeeResource\Pages\EditUser ? 'New Password' : 'Password';
                    })
                ,

                CheckboxList::make('roles')
                ->relationship('roles', 'name')
                ->columns(2)
                ->helperText('Only Choose One!')
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_display')->label("Employee")->searchable()->sortable(),
                Tables\Columns\TextColumn::make('grade.name'),
                Tables\Columns\TextColumn::make('gradeStep.name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('designation.name'),
                Tables\Columns\TextColumn::make('department.name'),
                Tables\Columns\TextColumn::make('section.name'),
                Tables\Columns\TextColumn::make('region.name'),
                // Tables\Columns\TextColumn::make('first_name'),
                // Tables\Columns\TextColumn::make('middle_name'),
                // Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('date_of_appointment')
                    ->date(),
                Tables\Columns\TextColumn::make('employee_display'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('employment_type'),
            ])
            ->filters([
                //
            ])
            ->actions([ 
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasEmployees::route('/'),
            'create' => Pages\CreateMasEmployee::route('/create'),
            'edit' => Pages\EditMasEmployee::route('/{record}/edit'),
        ];
    }    
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferClaimResource\Pages;
use App\Filament\Resources\TransferClaimResource\RelationManagers;
use App\Models\TransferClaim;
use App\Models\MasEmployee;
use App\Models\MasDesignation;
use App\Models\MasGradeStep;
use App\Models\department;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Closure;

class TransferClaimResource extends Resource
{
    protected static ?string $model = TransferClaim::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Expense';


    public static function form(Form $form): Form
    {
        $currentUserId = Auth::id();
        $user = MasEmployee::find($currentUserId);
        $emp_id = $user->emp_id;//Fetch Current users Emp_id
        //Fetch current users Designation
        $designationId = $user->designation_id;
        $designation = MasDesignation::find($designationId);
        $designationName = $designation->name;
        //Fetch current users Department
        $departmentId = $user->department_id;
        $department = department::find($departmentId);
        $departmentname = $department->name;
         //Fetch current users Basic Pay
         $gradestepId = $user->grade_step_id;
         $grade_step = MasGradeStep::find($gradestepId);
         $basic_pay = $grade_step->starting_salary;



        



        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                ->default($currentUserId)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('date')
                ->type('date')
                ->default(now()->toDateString())  // Set default value to current date
                ->disabled()  // Make the field disabled
                ->required(),
                Forms\Components\TextInput::make('employee_id')
                ->default($emp_id)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('designation')
                ->default($designationName)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('department')
                ->default($departmentname)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('basic_pay')
                ->default($basic_pay)
                ->disabled()
                ->required(),


            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTransferClaims::route('/'),
            'create' => Pages\CreateTransferClaim::route('/create'),
            'edit' => Pages\EditTransferClaim::route('/{record}/edit'),
        ];
    }    
}

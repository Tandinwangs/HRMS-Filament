<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelResource\Pages;
use App\Filament\Resources\FuelResource\RelationManagers;
use App\Models\Fuel;
use App\Models\vehicle;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Closure;
use Ramsey\Uuid\Type\Decimal;

class FuelResource extends Resource
{
    protected static ?string $model = Fuel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Expense';


    public static function form(Form $form): Form
    {
        $currentUserId = Auth::id();

        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                ->default($currentUserId)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('application_date')
                ->type('date')
                ->default(now()->toDateString())  // Set default value to current date
                ->disabled()  // Make the field disabled
                ->required(),
                Forms\Components\TextInput::make('location')
                ->required(),
                Forms\Components\Select::make('vehicle_no')
                ->options(
                    vehicle::all()->pluck('vehicle_number', 'id')->toArray()
                )
                ->label('Vechicle Number')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set){
                    $mileage = vehicle::whereRaw("id =?", [$state])->value("vehicle_mileage");
                    // dd($amount);
                    $set('mileage', $mileage);
                }),
                Forms\Components\DatePicker::make('date')
                ->required(),
                Forms\Components\TextInput::make('mileage')
                ->numeric()
                ->disabled()
                ->required()
                ->reactive(),
                Forms\Components\TextInput::make('initial_km')
                ->numeric()
                ->minValue(0)
                ->required()
                ->reactive(),
                Forms\Components\TextInput::make('final_km')
                ->numeric()
                ->minValue(0)
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set, $get){
                    $finalkm = $get('final_km');
                    $initialkm = $get('initial_km');
                    $mileage = $get('mileage');
                    // dd($amount);
                    $quantity = ($finalkm - $initialkm) / $mileage;
                    $set('quantity',$quantity);
                }),
                Forms\Components\TextInput::make('quantity')
                ->minValue(0)
                ->required()
                ->reactive(),
                Forms\Components\TextInput::make('rate')
                ->numeric()
                ->minValue(0)
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set, $get){
                    $rate = $get('rate');
                    $quantity = $get('quantity');
                    // dd($amount);
                    $amount = $rate * $quantity;
                    $set('amount',$amount);
                }),
                Forms\Components\TextInput::make('amount')
                ->numeric(10,2)
                ->disabled()
                ->required()
                ->reactive(),
                Forms\Components\FileUpload::make('attachment')
                ->preserveFilenames()                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('application_date')
                ->dateTime(),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('vehicle.vehicle_number'),
                Tables\Columns\TextColumn::make('mileage'),
                Tables\Columns\TextColumn::make('amount'),
                Tables\Columns\TextColumn::make('status'),








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
            'index' => Pages\ListFuels::route('/'),
            'create' => Pages\CreateFuel::route('/create'),
            'edit' => Pages\EditFuel::route('/{record}/edit'),
        ];
    }    
}

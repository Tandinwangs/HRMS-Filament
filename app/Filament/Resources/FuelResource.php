<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FuelResource\Pages;
use App\Filament\Resources\FuelResource\RelationManagers;
use App\Models\Fuel;
use App\Models\AddVehicle;
use App\Models\policy;
use App\Models\ExpenseType;
use App\Models\RateDefinition;
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
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class FuelResource extends Resource
{
    protected static ?string $model = Fuel::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Expense';

    protected static ?string $navigationLabel = 'Fuel';


    protected static ?int $navigationSort = 5;




    public static function form(Form $form): Form
    {
        $currentUserId = Auth::id();

       $expense = FUEL_ID;

       

        return $form
            ->schema([
                Forms\Components\Hidden::make('expense_type_id')
                ->default($expense)
                ->disabled()
                ->required()
                ->afterStateHydrated(function ($state, Closure $set){
                    $policy = policy::where('expense_type_id', $state)->value('id');
                    $attachment_required = RateDefinition::where('policy_id', $policy)->value('attachment_required');
                    $set('attachment_required', $attachment_required);
                }),
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
                    AddVehicle::all()->pluck('vehicle_number', 'id')->toArray()
                )
                ->label('Vechicle number')
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set){
                    $vehicle_type = AddVehicle::whereRaw("id =?", [$state])->value("vehicle_type");
                    $mileage = AddVehicle::whereRaw("id =?", [$state])->value("vehicle_mileage");
                    // dd($amount);
                    $set('vehicle_type', $vehicle_type);
                    $set('mileage', $mileage);

                }),
                Forms\Components\Select::make('vehicle_type')
                ->label('Vechicle type')
                ->searchable()
                ->required()
                ->reactive(),
                Forms\Components\DatePicker::make('date')
                ->required(),
                Forms\Components\TextInput::make('mileage')
                ->numeric()
                ->disabled()
                ->required()
                ->reactive()
                ->label("Mileage"),
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
                    $quantity = round($quantity, 2);
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
                    $amount = round($amount,2);
                    $set('amount',$amount);
                }),
                Forms\Components\TextInput::make('amount')
                ->numeric(10,2)
                ->disabled()
                ->required()
                ->reactive(),
                Forms\Components\FileUpload::make('attachment')
                ->preserveFilenames()
                ->required(function(callable $get){
                    if($get('attachment_required') == true){
                        return true;
                    }else{
                        return false;
                    }
                })                    
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Download')
                ->action(fn (Fuel $record) => FuelResource::downloadFile($record))
                ->hidden(function ( Fuel $record) {
                    return $record->attachment === null;
                })
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
    public static function downloadFile($record)
    {
        // Use Storage::url to generate the proper URL for the file
        $attachment = 'uploads/' . $record->attachment; // assuming 'public' is the disk name

        // Check if the file exists in storage
        if (!Storage::exists($attachment)) {
            abort(404, 'File not found');
        }
    
        return Storage::download($attachment);
    }   
}

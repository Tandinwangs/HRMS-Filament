<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplyAdvanceResource\Pages;
use App\Filament\Resources\ApplyAdvanceResource\RelationManagers;
use App\Models\ApplyAdvance;
use App\Models\DeviceEMI;
use App\Models\AdvanceType;
use App\Models\MasEmployee;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpOption\None;
use Closure;
use Ramsey\Uuid\Type\Decimal;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Label;

class ApplyAdvanceResource extends Resource
{
    protected static ?string $model = ApplyAdvance::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Apply';
    protected static ?string $navigationGroup = 'Advance/Loan';

    

    public static function form(Form $form): Form
    {
        $currentUserId = Auth::id();
        $currentDateTime = now();
        $user = MasEmployee::find($currentUserId);
        $empy_id = $user->emp_id;
        $advanceNo = 'ADL|EM|'.$empy_id.'|'.$currentDateTime->format('YmdHis');

        $advanceTypes = AdvanceType::all()->pluck('name', 'id')->toArray();




        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                ->default($currentUserId)
                ->disabled()
                ->required(),
                Forms\Components\Hidden::make('advance_no')
                ->default($advanceNo)
                ->disabled()
                ->required(),
                Forms\Components\TextInput::make('date')
                ->type('date')
                ->default(now()->toDateString())  // Set default value to current date
                ->disabled()  // Make the field disabled
                ->required(),
                Forms\Components\Select::make('advance_type_id')
                ->options(
                    AdvanceType::all()->pluck('name', 'id')->toArray()
                )
                ->label('Advance Type')
                ->required()
                ->reactive(),
                Forms\Components\Select::make('item_type')
                ->options(DeviceEMI::all()->pluck('type', 'id')->toArray())
                ->label('Item Type')
                ->reactive()
                ->searchable()
                ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    if (isset($selectedAdvanceTypeId) && array_key_exists($selectedAdvanceTypeId, $advanceTypes) && $advanceTypes[$selectedAdvanceTypeId] === "Device EMI") {
                        return true;
                    }
                    return false;
                })->afterStateUpdated(function ($state, Closure $set){
                    $amount = DeviceEMI::whereRaw("id =?", [$state])->value("amount");
                    // dd($amount);
                    $set('amount', $amount);
                }),
                Forms\Components\TextInput::make('amount')  
                ->required() 
                ->reactive()
                ->numeric()
                ->label('Amount')
                ->disabled(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    if (isset($selectedAdvanceTypeId) && array_key_exists($selectedAdvanceTypeId, $advanceTypes) && $advanceTypes[$selectedAdvanceTypeId] === "Device EMI") {
                        return true;
                    }
                    return false;
                })->afterStateUpdated(function ($state, Closure $set, $get){
                    $amount = null;
                    $set('interest_rate',$amount);
                    $set('emi_count',$amount);
                    $set('total_amount', $amount);
                    $set('monthly_emi_amount', $amount);

                    //dd($set('interest_rate',$amount));
                }),
                Forms\Components\select::make('mode_of_travel')
                ->options([
                    'Car' => 'Car',
                    'Bike'=>'Bike',
                    'Plain'=>'Plain',
                    'Train'=>'Train'                    
                ])->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "DSA Advance" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Advance To Staff"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\TextInput::make('from_location')
                ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "DSA Advance" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Advance To Staff"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\TextInput::make('to_location')
                ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "DSA Advance" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Advance To Staff"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\DatePicker::make('from_date')
                ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "DSA Advance" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Advance To Staff"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\DatePicker::make('to_date')
                ->required()
                ->after('from_date')
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "DSA Advance" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Advance To Staff"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\TextInput::make('interest_rate')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->reactive()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "SIFA Loan" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Device EMI"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                })->afterStateUpdated(function ($state, Closure $set, $get){
                    $amount = $get('amount');
                    // dd($amount);
                    $totalAmount = $amount + ($state * ($amount / 100));
                    $totalAmount = round($totalAmount,2);
                    $emi = null;
                    $set('total_amount',$totalAmount);
                    $set('emi_count', $emi);
                    $set('monthly_emi_amount',$emi);
                }),
                Forms\Components\TextInput::make('total_amount')
                ->numeric()
                ->minValue(0)
                // ->required()
                ->disabled()
                ->reactive()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "SIFA Loan" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Device EMI"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\TextInput::make('emi_count')
                ->numeric()
                ->minValue(0)
                ->required()
                ->reactive()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "SIFA Loan" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Device EMI" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Salary Advance"

                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                })->afterStateUpdated(function ($state, Closure $set, $get){
                    $totalamount = $get('total_amount');
                    // dd($amount);
                    $monthlyEMI = $totalamount / $state;
                    $monthlyEMI = round($monthlyEMI,2);

                    $set('monthly_emi_amount',$monthlyEMI);
                }),
                Forms\Components\TextInput::make('monthly_emi_amount')
                ->numeric()
                ->disabled()
                ->minValue(0)
                ->reactive()
                // ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "SIFA Loan" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Device EMI"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\DatePicker::make('deduction_period')
                ->required()
                ->visible(function ($get) use ($advanceTypes) {
                    $selectedAdvanceTypeId = $get('advance_type_id');
                    
                    // Check if the selected type is either "DSA Advance" or "Advance To Staff"
                    if (
                        isset($selectedAdvanceTypeId) &&
                        array_key_exists($selectedAdvanceTypeId, $advanceTypes) &&
                        (
                            $advanceTypes[$selectedAdvanceTypeId] === "SIFA Loan" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Device EMI" ||
                            $advanceTypes[$selectedAdvanceTypeId] === "Salary Advance"
                        )
                    ) {
                        return true;
                    }
                    
                    return false;
                }),
                Forms\Components\Textarea::make('purpose')
                ->rows(2),
                Forms\Components\FileUpload::make('upload_file')
                ->preserveFilenames()                   

          

            ]);
            

    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                // Tables\Columns\TextColumn::make('advance_no'),
                Tables\Columns\TextColumn::make('advanceType.name'),
                Tables\Columns\TextColumn::make('date')
                ->dateTime(),
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
                ->action(fn (ApplyAdvance $record) => ApplyAdvanceResource::downloadFile($record))
                ->hidden(function ( ApplyAdvance $record) {
                    return $record->upload_file === null;
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
            'index' => Pages\ListApplyAdvances::route('/'),
            'create' => Pages\CreateApplyAdvance::route('/create'),
            'edit' => Pages\EditApplyAdvance::route('/{record}/edit'),
        ];
    } 
    public static function downloadFile($record)
    {
        // Use Storage::url to generate the proper URL for the file
        $upload_file = 'uploads/' . $record->upload_file; // assuming 'public' is the disk name

        // Check if the file exists in storage
        if (!Storage::exists($upload_file)) {
            abort(404, 'File not found');
        }
    
        return Storage::download($upload_file);
    }   
}

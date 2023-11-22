<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransferClaimResource\Pages;
use App\Filament\Resources\TransferClaimResource\RelationManagers;
use App\Models\TransferClaim;
use App\Models\ExpenseType;
use App\Models\MasEmployee;
use App\Models\RateDefinition;
use App\Models\policy;
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
use Illuminate\Validation\ValidationException;
use Closure;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class TransferClaimResource extends Resource
{
    protected static ?string $model = TransferClaim::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Expense';
    protected static ?string $navigationLabel = 'Transfer Claim';
    protected static ?int $navigationSort = 6;




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

        //  $expenseType = ExpenseType::where('name', 'Transfer Claim')->first();
        //  if($expenseType){
        //     $expense = $expenseType->id;

        //  }else{
        //     $expense = null;
        //  }
        $expense = TRANSFER_CLAIM_ID;

       

        return $form
            ->schema([
                Forms\Components\Hidden::make('expense_type_id')
                ->label("Expense Type")
                ->default($expense)
                ->disabled()
                ->reactive()
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
                Forms\Components\Select::make('transfer_claim_type')
                ->options([
                    'Transfer Grant' => 'Transfer Grant',
                    'Carriage Charge' => 'Carriage Charge',
                ]) 
                ->label("Transfer Type")  
                ->reactive()         
                ->required(),
               
                Forms\Components\TextInput::make('current_location')
                ->required(),
                Forms\Components\TextInput::make('new_location')
                ->required(),
                Forms\Components\TextInput::make('distance_km')
                ->required()
                ->visible(function(callable $get){
                    if(in_array((string)$get('transfer_claim_type'),["Carriage Charge"])){
                        return true;
                    }else{
                        return false;
                    }
                }),
                Forms\Components\TextInput::make('claim_amount')
                ->required(),
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
                Tables\Columns\TextColumn::make('date')
                ->dateTime(),
                Tables\Columns\TextColumn::make('transfer_claim_type'),
                Tables\Columns\TextColumn::make('claim_amount'),
                Tables\Columns\TextColumn::make('current_location'),
                Tables\Columns\TextColumn::make('new_location'),
                Tables\Columns\TextColumn::make('status'),




            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('Download')
                ->action(fn (TransferClaim $record) => TransferClaimResource::downloadFile($record))
                ->hidden(function ( TransferClaim $record) {
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
            'index' => Pages\ListTransferClaims::route('/'),
            'create' => Pages\CreateTransferClaim::route('/create'),
            'edit' => Pages\EditTransferClaim::route('/{record}/edit'),
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

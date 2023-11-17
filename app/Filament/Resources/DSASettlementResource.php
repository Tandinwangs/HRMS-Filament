<?php

namespace App\Filament\Resources;

use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\DSASettlementResource\Pages;
use App\Filament\Resources\DSASettlementResource\RelationManagers;
use App\Models\ApplyAdvance;
use App\Models\RateLimit;
use App\Models\policy;
use App\Models\RateDefinition;
use App\Models\DSAManual;
use App\Models\DSASettlement;
use App\Models\ExpenseType;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Closure;
use Symfony\Contracts\Service\Attribute\Required;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Storage;

class DSASettlementResource extends Resource
{
    protected static ?string $model = DSASettlement::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Expense';


    public static function form(Form $form): Form
     {
        $expenseType = ExpenseType::where('name', 'DSA Settlement')->first();
        if($expenseType){
            $expense = $expenseType->id;

         }else{
            $expense = null;
         }                    
        if ($expenseType) {
            // Find the Policies associated with the ExpenseType
            $policies = $expenseType->policies;
            
            // Get policy IDs
            $policyIds = $policies->pluck('id')->toArray();
            
            // Find the RateLimits with the same policy ID and user's grade
            $rateLimits = RateLimit::whereIn('policy_id', $policyIds)
                ->where('grade', Auth::user()->grade_id)
                ->get();
            
            // Check if there are matching rate limits
            if ($rateLimits->isEmpty()) {
                $da = 0; // Handle the case where rate limits with the user's grade don't exist
            } else {
                // You can choose how to handle multiple rate limits here; for now, let's take the first one
                $rateLimit = $rateLimits->first();
                $da = $rateLimit->limit_amount;
            }
        } else {
            $da = "no da set"; // Handle the case where the DSA policy doesn't exist
        }


        $currentUserId = Auth::id();
        $user = Auth::user();

        $userAdvances = ApplyAdvance::whereHas('advanceType', function ($query) {
            $query->where('name', 'DSA Advance');
            })
            ->where('status', 'approved')
            ->where('user_id', $user->id) 
            ->pluck('advance_no', 'id');
            //dd($userAdvances);

        // Get the IDs of advances that exist in the dsa_settlements table
        $existingAdvanceIds = DB::table('d_s_a_settlements')->pluck('advance_no');

        // Filter the user's advances to include only those that do not exist in the dsa_settlements table
        $userAdvances = $userAdvances->filter(function ($advanceNo, $id) use ($existingAdvanceIds) {
            return !$existingAdvanceIds->contains($id);
        });

        return $form
            ->schema([
                Forms\Components\Hidden::make('expensetype_id')
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
                Forms\Components\Select::make('advance_no')
                ->options($userAdvances)
                //->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Closure $set){
                    $advance = ApplyAdvance::whereRaw("id =?", [$state])->value("amount");
                    $set('advance_amount', $advance);
                    $set('total_amount_adjusted', $advance);
                    $set('net_payable_amount', $advance);
                    $set('balance_amount', 0); 
                }),
                Forms\Components\TextInput::make('advance_amount')
                //->required()
                ->disabled()
                ->default(0)
                ->reactive(),
                Forms\Components\TextInput::make('total_amount_adjusted')
                //->required()
                ->disabled()
                ->reactive(),
                Forms\Components\TextInput::make('net_payable_amount')
                //->required()
                ->disabled()
                ->reactive(),
                Forms\Components\TextInput::make('balance_amount')
                //->required()
                ->disabled(),
                Forms\Components\FileUpload::make('upload_file')
                ->preserveFilenames()
                ->required(function(callable $get){
                    if($get('attachment_required') == true){
                        return true;
                    }else{
                        return false;
                    }
                }),

                
                Forms\Components\Card::make()
                ->afterStateUpdated(function ( Closure $set, $get){
                    $totalAmount = $get('total_amount');
                     dd($totalAmount);
                    $set('total_amount_adjusted', $totalAmount);
                    $set('net_payable_amount', $totalAmount);

                })
                ->schema([
                 Forms\Components\Repeater::make('DSAManual')
                ->columns(5)
                ->reactive()
                ->visible(function ($get) use ($userAdvances){
                    if($userAdvances->isEmpty()) {
                        return true;
                    }
                    return false;

                })
                ->relationship()
                ->columnSpanFull()
                ->schema([
                    Forms\Components\Hidden::make('user_id')
                    ->default($currentUserId)
                    ->disabled()
                    ->required(),
                    Forms\Components\DatePicker::make('from_date')
                    ->reactive()
                    ->required(),
                    Forms\Components\TextInput::make('from_location')
                    ->required(),
                    Forms\Components\DatePicker::make('to_date')
                    ->reactive()
                    ->required()
                    ->afterOrEqual('from_date')
                    ->afterStateUpdated(function ($state, Closure $set, $get){
                        $fromDate = strtotime($get('from_date'));
                            $endDate = strtotime($state);
                            $diff = $endDate - $fromDate;
                        
                            // Calculate the number of full days
                            $numberOfDays = floor($diff / (24 * 60 * 60)) + 1;
                            $set('total_days', $numberOfDays);
                    }),
                    Forms\Components\TextInput::make('to_location')
                    ->required(),
                    Forms\Components\TextInput::make('total_days')
                    ->reactive()
                    ->required()
                    ->numeric(), 
                    Forms\Components\TextInput::make('da')
                    ->reactive()
                    ->default($da)
                   ->required()
                   ->disabled(),
                    Forms\Components\TextInput::make('ta')
                    ->reactive()
                    ->numeric()
                    ->required()
                    ->afterStateUpdated(function ($state, Closure $set, $get){
                        $totaldays = $get('total_days');
                        $da = $get('da');
                        // dd($amount);
                        $totalAmount = ($da*$totaldays)+$state;
                        $set('total_amount',$totalAmount);
                        $set('total_amount_adjusted', $totalAmount);
                        $set('net_payable_amount', $totalAmount);

                    }),
                    Forms\Components\TextInput::make('total_amount')
                    ->required()
                    ->disabled(),
                    Forms\Components\Textarea::make('remarks')
                    ->rows(1)
                    ->required(),
                ])->createItemButtonLabel('Add')
                ]) 
            ]);
    }
  
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('advance.advance_no'),
                Tables\Columns\TextColumn::make('date')
                ->dateTime(),
                Tables\Columns\TextColumn::make('total_amount_adjusted'),
                Tables\Columns\TextColumn::make('advance_amount'),
                Tables\Columns\TextColumn::make('status'),            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Download')
                ->action(fn (DSASettlement $record) => DSASettlementResource::downloadFile($record))
                ->hidden(function ( DSASettlement $record) {
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
            'index' => Pages\ListDSASettlements::route('/'),
            'create' => Pages\CreateDSASettlement::route('/create'),
            'edit' => Pages\EditDSASettlement::route('/{record}/edit'),
        ];
    } 
    public static function downloadFile($record)
    {
        // Use Storage::url to generate the proper URL for the file
        $upload_file = 'uploads/' . $record->upload_file; // assuming 'uploads' is the disk name

        // Check if the file exists in storage
        if (!Storage::exists($upload_file)) {
            abort(404, 'File not found');
        }
    
        return Storage::download($upload_file);
    }     
}

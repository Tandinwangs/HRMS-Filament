<?php

namespace App\Filament\Resources\LeavePolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeavePlanRelationManager extends RelationManager
{
    protected static string $relationship = 'LeavePlan';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Toggle::make('attachment_required')
                ->required(),
            Forms\Components\Select::make('gender')->options([
                'M'=> "Male",
                'F'=> "Female",
                'A'=> "All"
            ])->required(),
            Forms\Components\Select::make('leave_year')->options([
                'FY'=> "Financial Year",
                'CY'=> "Calendar Year",
            ])->required(),
            Forms\Components\Select::make('credit_frequency')->options([
                'montly'=> "Monthly",
                'yearly'=> "Yearly",
            ])->required(),
            Forms\Components\Select::make('credit')->options([
                'Start Of Period'=> "Start Of Period",
                'End Of Period'=> "End Of Period",
            ])->required(),
            Forms\Components\Toggle::make('include_public_holidays')
                ->required(),
            Forms\Components\Toggle::make('include_weekends')
                ->required(),
            Forms\Components\Toggle::make('can_be_clubbed_with_el')
                ->required(),
            Forms\Components\Toggle::make('can_be_clubbed_with_cl')
                ->required(),
            Forms\Components\Toggle::make('can_be_half_day')
                ->required(),
            Forms\Components\Toggle::make('probation_period')
                ->required(),
            Forms\Components\Toggle::make('regular_period')
                ->required(),
            Forms\Components\Toggle::make('contract_period')
                ->required(),
            Forms\Components\Toggle::make('notice_period')
                ->required(),
        ]);
    }

    public function saveRecord($record, Form $form)
    {
        try {
            // Save the record as usual
            parent::saveRecord($record, $form);
            
            // Log success
            \Log::info('Record saved successfully.');
        } catch (\PDOException $e) {
            // Log the exception for debugging
            \Log::error('Exception in LeavePlanRelationManager: ' . $e->getMessage());
    
            // Log the exception code for debugging
            \Log::error('Exception Code: ' . $e->getCode());
    
            // Check if the exception is due to a unique constraint violation
            if ($e->getCode() === '23000') {
                // Add an error message to the form
                $form->addComponent(
                    Alert::make('Error')
                        ->message('The Policy ID must be unique.')
                        ->variant('danger')
                );
            } else {
                // Handle other PDO exceptions
                throw $e;
            }
        }
    }
    
    
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('policy.policy_name'),
            Tables\Columns\IconColumn::make('attachment_required')
                ->boolean(),
            Tables\Columns\TextColumn::make('gender'),
            Tables\Columns\TextColumn::make('leave_year'),
            Tables\Columns\TextColumn::make('credit_frequency'),
            Tables\Columns\TextColumn::make('credit'),
            Tables\Columns\IconColumn::make('include_public_holidays')
                ->boolean(),
            Tables\Columns\IconColumn::make('include_weekends')
                ->boolean(),
            Tables\Columns\IconColumn::make('can_be_clubbed_with_el')
                ->boolean(),
            Tables\Columns\IconColumn::make('can_be_clubbed_with_cl')
                ->boolean(),
            Tables\Columns\IconColumn::make('can_be_half_day')
                ->boolean(),
            Tables\Columns\IconColumn::make('probation_period')
                ->boolean(),
            Tables\Columns\IconColumn::make('regular_period')
                ->boolean(),
            Tables\Columns\IconColumn::make('contract_period')
                ->boolean(),
            Tables\Columns\IconColumn::make('notice_period')
                ->boolean(),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}

<?php

namespace App\Filament\Resources\LeavePolicyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\MasGradeStep;

class LeaveRulesRelationManager extends RelationManager
{
    protected static string $relationship = 'LeaveRules';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('grade_step_id')
            ->options(
                MasGradeStep::all()->pluck('name', 'id')->toArray()
            )
            ->required(),
            Forms\Components\TextInput::make('duration')
                ->required(),
            Forms\Components\Select::make('uom')->options([
                'Day'=> "Day",
                'Month'=> "Month",
                'Year'=> "Year"
            ])->required(),
            Forms\Components\DatePicker::make('start_date')
                ->required(),
            Forms\Components\DatePicker::make('end_date'),
                Forms\Components\Select::make('islossofpay')->options([
                    1=> "Yes",
                    0=> "No",
                ])->required(),
            Forms\Components\Select::make('employee_type')->options([
                'regular_period'=> "Regular",
                'probation_period'=> "Probation",
                'contract_period' => "Contract",
                'notice_period' => "Notice",
            ])->required(),
            Forms\Components\Toggle::make('status')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('policy.policy_name'),
            Tables\Columns\TextColumn::make('gradeStep.name'),
            Tables\Columns\TextColumn::make('duration'),
            Tables\Columns\TextColumn::make('uom'),
            Tables\Columns\TextColumn::make('start_date')
                ->date(),
            Tables\Columns\TextColumn::make('end_date')
                ->date(),
            Tables\Columns\IconColumn::make('islossofpay')
                ->boolean(),
            Tables\Columns\TextColumn::make('employee_type'),
            Tables\Columns\IconColumn::make('status')
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

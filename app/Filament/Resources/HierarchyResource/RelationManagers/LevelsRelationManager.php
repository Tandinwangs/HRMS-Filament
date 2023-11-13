<?php

namespace App\Filament\Resources\HierarchyResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\MasEmployee;

class LevelsRelationManager extends RelationManager
{
    protected static string $relationship = 'levels';

    protected static ?string $recordTitleAttribute = 'hierarchy_id';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('level')->options([
                1=> "Level1",
                2=> "Level2",
                3 => "Level3", 
            ])->required(),
            Forms\Components\Select::make('value')->options([
                'IS' => "Immediate Supervisor",
                'SH'=> "Section Head",
                'DH'=> "Department Head",
                'MM' => "Management",
                'HR' => "Human Resource",
                'FH' => "Finance Head"
            ])->required()->reactive(),
            
            Forms\Components\Select::make('emp_id')
            ->options(
                MasEmployee::all()->pluck('name', 'id')->toArray()
            )
            ->visible(function(callable $get){
                if(in_array((string)$get('value'),["MM", "HR", "FH"])){
                    return true;
                }else{
                    return false;
                }})
            ->required(function(callable $get){
                if(in_array((string)$get('value'),["MM", "HR", "FH"])){
                    return true;
                }else{
                    return false;
                    }
                })
          ,            
            Forms\Components\DatePicker::make('start_date')
                ->required(),
            Forms\Components\DatePicker::make('end_date')
                ->required(),
            Forms\Components\Toggle::make('status')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('hierarchy.name'),
            Tables\Columns\TextColumn::make('emp_id'),
            Tables\Columns\TextColumn::make('level'),
            Tables\Columns\TextColumn::make('value'),
            Tables\Columns\TextColumn::make('start_date')
                ->date(),
            Tables\Columns\TextColumn::make('end_date')
                ->date(),
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

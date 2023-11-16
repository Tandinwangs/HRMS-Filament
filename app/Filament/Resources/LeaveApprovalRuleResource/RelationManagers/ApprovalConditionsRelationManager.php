<?php

namespace App\Filament\Resources\LeaveApprovalRuleResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\LeaveApprovalConditionResource\RelationManagers;
use App\Filament\Resources\LeaveApprovalRuleResource\RelationManagers\LeaveFormulasRelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Hierarchy;
use App\Models\Level;
use App\Models\MasEmployee;

class ApprovalConditionsRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalConditions';

    protected static ?string $recordTitleAttribute = 'approval_rule_id';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('approval_type')->options([
             "Hierarchy" => "Hierarchy",
             "Single User" => "Single User" ,
             "Auto Approval" => "Auto Approval" 
            ])->required()->reactive(),

            Forms\Components\Select::make('hierarchy_id')->options(
                Hierarchy::all()->pluck('name', 'id')->toArray()
            ) ->default(Hierarchy::first()->id)
            ->visible(function(callable $get){
                if(in_array((string)$get('approval_type'),["Hierarchy"])){
                    return true;
                }else{
                    return false;
                }
            })->required(function(callable $get){
                if(in_array((string)$get('approval_type'),["Hierarchy"])){
                    return true;
                }else{
                    return false;
                }
            })->reactive(),
            Forms\Components\Select::make('MaxLevel')->options(function (callable $get) {
                            // Get the selected hierarchy_id
                $hierarchyId = $get('hierarchy_id');

                // Fetch levels based on hierarchyId (replace this with your actual logic)
                $levels = Level::where('hierarchy_id', $hierarchyId)->pluck('level', 'id')->toArray();

                // Add an empty option
                $options = [];

                // Prepend "Level" to each option label and store the option itself
                foreach ($levels as $id => $level) {
                    $options['Level' . $level] = 'Level' . $level;
                }

                return $options;
            })
            ->visible(function(callable $get){
                if(in_array((string)$get('approval_type'),["Hierarchy"])){
                    return true;
                }else{
                    return false;
                }
            })->required(function(callable $get){
                if(in_array((string)$get('approval_type'),["Hierarchy"])){
                    return true;
                }else{
                    return false;
                }
            }),
  
            Forms\Components\Select::make('employee_id')->options(
                MasEmployee::all()->pluck('name', 'id')->toArray()
            )
            ->visible(function(callable $get){
                if(in_array((string)$get('approval_type'),["Single User"])){
                    return true;
                }else{
                    return false;
                }
            })->required(function(callable $get){
                if(in_array((string)$get('approval_type'),["Single User"])){
                    return true;
                }else{
                    return false;
                }
            })
            ,
           
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('approval_type'),
            // Tables\Columns\TextColumn::make('MaxLevel'),
            // Tables\Columns\TextColumn::make('hierarchy.name'),
            // Tables\Columns\TextColumn::make('employee_id'),
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
    
    public static function getRelations(): array
    {
        return [
            LeaveFormulasRelationManager::class,
        ];
    }
}

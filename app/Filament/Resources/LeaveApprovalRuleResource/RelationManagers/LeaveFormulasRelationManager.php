<?php

namespace App\Filament\Resources\LeaveApprovalRuleResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\MasEmployee;
use App\Models\LeaveFormula;

class LeaveFormulasRelationManager extends RelationManager
{
    protected static string $relationship = 'LeaveFormulas';

    protected static ?string $recordTitleAttribute = 'approval_rule_id';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('condition')
            ->options([
                'AND' => 'AND',
                'OR' => 'OR',
                'NOT' => 'NOT',
            ]),
            Forms\Components\Select::make('field')
            ->options([
                'User' => 'User',
                'No. of Days' => 'No. of Days',
            ])->required()->reactive(),
            Forms\Components\Select::make('operator')
            ->options([
                'Is' => 'Is',
                'Is Not' => 'Is Not',
                'Is Greater Than' => 'Is Greater Than',
                'Is Less Than' => 'Is Less Than',
                'Is Less Than or Equal To' => 'Is Less Than or Equal To',
                'Is Greater Than or Equal To' => 'Is Greater Than or Equal To',
                // Add other operator options as needed
            ])->required(),
            Forms\Components\Select::make('employee_id')->options(
                MasEmployee::all()->pluck('name', 'id')->toArray()
            )
                ->visible(function(callable $get){
                    if(in_array((string)$get('field'),["User"])){
                        return true;
                    }else{
                        return false;
                    }
                })
                ->required(function(callable $get){
                    if(in_array((string)$get('field'),["User"])){
                        return true;
                    }else{
                        return false;
                    }
                })
            ,     
            Forms\Components\TextInput::make('value')
            ->type('number')
            ->visible(function(callable $get){
                if(in_array((string)$get('field'),["No. of Days"])){
                    return true;
                }else{
                    return false;
                }
            })
            ->required(function(callable $get){
                if(in_array((string)$get('field'),["No. of Days"])){
                    return true;
                }else{
                    return false;
                }
            }),
        ]);
    }

    public function onSave($record, array $data)
    {
        parent::onSave($record, $data);

        // Build the new formula line based on the submitted form data
        $newFormulaLine = $this->buildFormulaLine($data);

        // Check if there's an existing formula for the approval_rule_id
        $existingFormula = LeaveFormula::where('approval_rule_id', $data['approval_rule_id'])->first();

        // If an existing formula is found, concatenate the new formula line
        if ($existingFormula) {
            $existingFormula->formula_display .= " $newFormulaLine";
            $existingFormula->save();
        } else {
            // If no existing formula, create a new one
            LeaveFormula::create([
                'approval_rule_id' => $data['approval_rule_id'],
                'formula_display' => $newFormulaLine,
            ]);
        }
    }

    // Implement a method to build the new formula line based on the form data
    protected function buildFormulaLine(array $data)
    {
        // Implement your logic to construct the new formula line from $data
        // For example, concatenate condition, field, operator, value, employee_id
        $formulaLine = "{$data['condition']} {$data['field']} {$data['operator']} {$data['value']} {$data['employee_id']}";

        return $formulaLine;
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('formula_display'),
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

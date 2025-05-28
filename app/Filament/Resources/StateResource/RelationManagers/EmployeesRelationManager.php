<?php

namespace App\Filament\Resources\StateResource\RelationManagers;

use App\Models\Country;
use App\Models\Employee;
use App\Models\State;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Select::make('country_id')
                        ->label('Country')
                        ->options(Country::all()->pluck('name', 'id')->toArray())
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('state_id', null))
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),
                    Select::make('state_id')
                        ->label('State')
                        ->options(function (callable $get) {
                            $country = Country::find($get('country_id'));
                            if(!$country) {
                                return State::all()->pluck('name', 'id');
                            }
                            return $country->states->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),
                    Select::make('city_id')
                        ->label('City')
                        ->options(function (callable $get) {
                            $state = State::find($get('state_id'));
                            if(!$state) {
                                return City::all()->pluck('name', 'id');
                            }
                            return $state->cities->pluck('name', 'id');
                        })
                        ->required()
                        ->reactive(),
                    Select::make('department_id')
                        ->relationship('department', 'name')->required(),
                    TextInput::make('first_name')->required()->maxLength(255),
                    TextInput::make('last_name')->required()->maxLength(255),
                    TextInput::make('address')->required()->maxLength(255),
                    TextInput::make('zip_code')->required()->maxLength(5),
                    DatePicker::make('birth_date')->required(),
                    DatePicker::make('date_hired')->required()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable()->searchable(),
                TextColumn::make('date_hired')->date()
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

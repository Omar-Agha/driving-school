<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewSchool extends ViewRecord
{
    protected static string $resource = SchoolResource::class;


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make("Base seciton")->schema([

                    Split::make([
                        ImageEntry::make("avatar")->grow(false),

                        Grid::make(2)->schema([
                            Group::make([
                                TextEntry::make("school_name")
                            ]),
                            Group::make([
                                Fieldset::make("Credential Info")->schema([
                                    TextEntry::make("user.email")
                                    ->label("Email"),
                                    TextEntry::make("user.username")
                                    ->label("Username"),

                                ])

                            ])

                        ])->grow(true),

                    ])->from("lg"),
                ])

            ]);
    }

}
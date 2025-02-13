<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->after( 
                function(Category $record) {
                    if($record->image) {
                        Storage::disk('public')->delete($record->image);
                    }   
                }
            ),
        ];
    }
}

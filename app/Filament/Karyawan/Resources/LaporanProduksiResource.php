<?php

namespace App\Filament\Karyawan\Resources;

use App\Models\LaporanProduksi;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Karyawan\Resources\LaporanProduksiResource\Pages;

class LaporanProduksiResource extends Resource
{
    protected static ?string $model = LaporanProduksi::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Laporan Produksi';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Produksi')
                    ->default(now())
                    ->required(),

                Forms\Components\TextInput::make('jenis_pekerjaan')
                    ->label('Jenis Pekerjaan')
                    ->required(),

                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah Produksi')
                    ->numeric()
                    ->required(),

                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Bukti')
                    ->image()
                    ->directory('laporan-foto'),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan'),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),

                Forms\Components\Hidden::make('status')
                    ->default('menunggu'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jenis_pekerjaan')
                    ->label('Pekerjaan'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'menunggu',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ]),

                Tables\Columns\TextColumn::make('alasan_penolakan')
                    ->label('Keterangan')
                    ->limit(50)
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->status === 'menunggu'),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanProduksis::route('/'),
            'create' => Pages\CreateLaporanProduksi::route('/create'),
            'edit' => Pages\EditLaporanProduksi::route('/{record}/edit'),
        ];
    }
}

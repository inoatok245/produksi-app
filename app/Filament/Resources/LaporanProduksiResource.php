<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanProduksiResource\Pages;
use App\Models\LaporanProduksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Exports\ArrayExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanProduksiResource extends Resource
{
    protected static ?string $model = LaporanProduksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Laporan Produksi';

   public static function form(Form $form): Form
{
    $user = Auth::user();
    $isAdmin = $user->isAdmin();

    return $form
        ->schema([
            Forms\Components\DatePicker::make('tanggal')
                ->label('Tanggal Produksi')
                ->required()
                ->default(now())
                ->disabled($isAdmin),

            Forms\Components\TextInput::make('jenis_pekerjaan')
                ->label('Jenis Pekerjaan')
                ->required()
                ->disabled($isAdmin),

            Forms\Components\TextInput::make('jumlah')
                ->label('Jumlah Produksi')
                ->numeric()
                ->required()
                ->disabled($isAdmin),

            Forms\Components\Textarea::make('keterangan')
                ->label('Keterangan')
                ->disabled($isAdmin),

            Forms\Components\FileUpload::make('foto')
                ->label('Foto Bukti')
                ->image()
                ->directory('laporan-foto')
                ->disabled($isAdmin),

            Forms\Components\Hidden::make('user_id')
                ->default($user->id),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'menunggu' => 'Menunggu',
                    'disetujui' => 'Disetujui',
                    'ditolak' => 'Ditolak',
                ])
                ->default('menunggu')
                ->visible($isAdmin)
                ->required(),

            Forms\Components\Textarea::make('alasan_penolakan')
                ->label('Alasan Penolakan')
                ->visible($isAdmin),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->searchable(),

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
                    ->label('Alasan Penolakan')
                    ->limit(30)
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function ($record) {
                        $user = Auth::user();
                        return $user->isAdmin() || ($user->id === $record->user_id && $record->status === 'menunggu');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('export')
                        ->label('Export ke Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $data = $records->map(function ($laporan) {
                                return [
                                    'Nama Karyawan'     => $laporan->user->name,
                                    'Tanggal'           => $laporan->tanggal,
                                    'Jenis Pekerjaan'   => $laporan->jenis_pekerjaan,
                                    'Jumlah'            => $laporan->jumlah,
                                    'Status'            => $laporan->status,
                                    'Keterangan'        => $laporan->keterangan,
                                ];
                            });

                            return Excel::download(new ArrayExport($data), 'laporan-produksi-terpilih.xlsx');
                        })
                        ->deselectRecordsAfterCompletion()
                        ->visible(fn () => Auth::user()->isAdmin()),
                ]),
            ]);
    }

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery(); // Tampilkan semua data
}



    public static function getRelations(): array
    {
        return [];
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

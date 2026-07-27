<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]
class BookManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterCategory = '';

    public ?int $bookId = null; // Tanda ? artinya boleh null saat tambah buku
    public ?string $title = null;
    public ?string $author = null;
    public ?string $publisher = null;
    public ?string $published_place = null;
    public ?int $published_year = null;
    public ?string $edition = null;
    public ?int $stock = 0;
    public ?string $language = null;
    public ?string $isbn = null;
    public ?string $description = null;
    public mixed $cover = null; // mixed karena bisa berupa file upload atau string path
    public ?string $category = null;
    public ?string $ddc = null;

    public ?string $existingCover = null;

    public $isOpen = false;
    public $isEditMode = false;
    public $isImportOpen = false;
    public mixed $fileExcel = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Aturan Validasi
    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'published_place' => 'nullable|string|max:255',
            'published_year' => 'required|integer|digits:4|max:' . date('Y'),
            'edition' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'language' => 'required|string|max:100',
            'isbn' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'cover' => $this->cover instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile 
                        ? 'nullable|image|max:2048' 
                        : 'nullable',
            'category' => 'required|string|max:100',
            'ddc' => 'required|string|max:50',
        ];
    }

    protected $messages = [
        'required' => 'Kolom :attribute wajib diisi.',
        'integer' => 'Kolom :attribute harus berupa angka.',
        'min' => 'Kolom :attribute tidak boleh kurang dari :min.',
        'image' => 'File harus berupa gambar.',
        'max' => 'Ukuran file :attribute maksimal 2MB.',
        'digits' => 'Tahun harus terdiri dari 4 digit.',
    ];

    protected $validationAttributes = [
        'title' => 'Judul Buku',
        'author' => 'Penulis',
        'publisher' => 'Penerbit',
        'published_year' => 'Tahun Terbit',
        'stock' => 'Stok',
        'language' => 'Bahasa',
        'category' => 'Kategori',
        'ddc' => 'DDC',
    ];

    // Fungsi Buka/Tutup Modal
    public function openModal() { $this->isOpen = true; }
    public function closeModal() 
    { 
        $this->isOpen = false; 
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['bookId', 'title', 'author', 'publisher', 'published_place', 'published_year', 'edition', 'stock', 'language', 'isbn', 'description', 'cover', 'existingCover', 'category', 'ddc', 'isEditMode']);
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        $coverPath = null;
        if ($this->cover) {
            // ✅ Ganti 'public' menjadi 's3'
            $coverPath = $this->cover->store('covers', 'public');
        }

        Book::create([
            'title'          => $this->title,
            'author'         => $this->author,
            'publisher'      => $this->publisher,
            'published_place' => $this->published_place,
            'published_year' => $this->published_year,
            'edition'        => $this->edition,
            'stock'          => $this->stock,
            'language'       => $this->language,
            'isbn'           => $this->isbn,
            'description'    => $this->description,
            'cover'          => $coverPath,
            'category'       => $this->category,
            'ddc'            => $this->ddc,
        ]);

        session()->flash('success', 'Buku berhasil ditambahkan!');
        $this->closeModal();
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->isEditMode = true;
        
        $book = Book::findOrFail($id);
        $this->bookId = $book->id;
        $this->title = $book->title;
        $this->author = $book->author;
        $this->publisher = $book->publisher;
        $this->published_place = $book->published_place;
        $this->published_year = $book->published_year;
        $this->edition = $book->edition;
        $this->stock = $book->stock;
        $this->language = $book->language;
        $this->isbn = $book->isbn;
        $this->description = $book->description;
        $this->existingCover = $book->cover;
        $this->category = $book->category;
        $this->ddc = $book->ddc;

        $this->openModal();
    }

    public function update()
    {
        $this->validate();
        $book = Book::findOrFail($this->bookId);

        $coverPath = $book->cover;
        if ($this->cover instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
            if ($book->cover) {
                // ✅ Ganti 'public' menjadi 's3'
                Storage::disk('public')->delete($book->cover);
            }
            // ✅ Ganti 'public' menjadi 's3'
            $coverPath = $this->cover->store('covers', 'public');
        }

        $book->update([
            'title'          => $this->title,
            'author'         => $this->author,
            'publisher'      => $this->publisher,
            'published_place' => $this->published_place,
            'published_year' => $this->published_year,
            'edition'        => $this->edition,
            'stock'          => $this->stock,
            'language'       => $this->language,
            'isbn'           => $this->isbn,
            'description'    => $this->description,
            'cover'          => $coverPath,
            'category'       => $this->category,
            'ddc'            => $this->ddc,
        ]);

        session()->flash('success', 'Data buku berhasil diperbarui!');
        $this->closeModal();
    }

    public function delete($id)
    {
        $book = Book::findOrFail($id);

        if ($book->borrowings()->count() > 0) {
            session()->flash('error', 'Buku tidak boleh dihapus karena sedang dipinjam oleh siswa!');
            return;
        }

        if ($book->cover) {
            // ✅ Ganti 'public' menjadi 's3'
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();
        session()->flash('success', 'Buku sukses dihapus dari sistem.');
    }

    // Fungsi Kontrol Modal Import
        public function openImportModal()
        {
            $this->fileExcel = null;
            $this->isImportOpen = true;
        }

        public function closeImportModal()
        {
            $this->isImportOpen = false;
            $this->fileExcel = null;
        }

        // Fungsi Eksekusi Proses Import & Upsert Massal
        public function importExcel()
        {
            $this->validate([
                'fileExcel' => 'required|mimes:xlsx,xls,csv|max:10240', // Maksimal 10MB
            ], [
                'fileExcel.required' => 'Pilih file terlebih dahulu.',
                'fileExcel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
                'fileExcel.max' => 'Ukuran file maksimal adalah 10MB.',
            ]);

            try {
                // Jalankan import langsung memanfaatkan file temporer dari Livewire
                \Maatwebsite\Excel\Facades\Excel::import(
                    new \App\Imports\BooksImport, 
                    $this->fileExcel->getRealPath()
                );

                session()->flash('success', 'Data Excel koleksi buku berhasil di-import dan di-upsert secara massal!');
                $this->closeImportModal();
            } catch (\Exception $e) {
                session()->flash('error', 'Terjadi kesalahan saat meng-import data: ' . $e->getMessage());
            }
        }


    public function render()
    {
        // Mengambil daftar kategori unik dari data yang ada untuk filter dropdown
        $categories = Book::select('category')->distinct()->pluck('category');

        $books = Book::query()
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('author', 'like', '%' . $this->search . '%')
                        ->orWhere('isbn', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterCategory, function($query) {
                $query->where('category', $this->filterCategory);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.book-management', [
            'books' => $books,
            'categories' => $categories
        ]);
    }
}

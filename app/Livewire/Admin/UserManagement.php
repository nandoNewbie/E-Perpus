<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin-layout')]

class UserManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Properti Filter & Search
    public $search = '';
    public $filterRole = '';

    // Properti Modal Form CRUD
    public $isOpen = false;
    public $isEditMode = false;
    public mixed $userId, $name, $identity_number, $class, $role = 'siswa';

    // Properti Modal Import
    public $isImportOpen = false;
    public mixed $fileExcel = null;

    protected $updatesQueryString = ['search', 'filterRole'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('identity_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterRole, function($query) {
                $query->where('role', $this->filterRole);
            })
            ->latest()
            ->paginate(10);

        // Mengambil daftar role unik untuk dropdown filter
        $roles = ['siswa', 'guru', 'pustakawan']; 

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    // --- LOGIKA MODAL CRUD ---
    public function create()
    {
        $this->resetInputFields();
        $this->isEditMode = false;
        $this->isOpen = true;
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->class = $user->class;
        $this->identity_number = $user->identity_number;
        $this->role = $user->role;

        $this->isEditMode = true;
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->class = '';
        $this->identity_number = '';
        $this->role = 'siswa';
    }

    public function store()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'identity_number' => 'required|unique:users,identity_number',
            'role'  => 'required|string',
        ]);

        User::create([
            'name'     => $this->name,
            'class'    => $this->class,
            'identity_number' => $this->identity_number,
            'password' => Hash::make('password123'), // Default password
            'role'     => $this->role,
        ]);

        session()->flash('success', 'Anggota baru berhasil ditambahkan!');
        $this->closeModal();
    }

    public function update()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'identity_number' => 'required|unique:users,identity_number,' . $this->userId,
            'role'  => 'required|string',
        ]);

        $user = User::findOrFail($this->userId);
        $user->update([
            'name'  => $this->name,
            'class'    => $this->class,
            'identity_number' => $this->identity_number,
            'role'  => $this->role,
        ]);

        session()->flash('success', 'Data Anggota berhasil diperbarui!');
        $this->closeModal();
    }

    public function delete(string $id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Anggota berhasil dihapus!');
    }

    // --- LOGIKA MODAL IMPORT EXCEL ---
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

    public function importExcel()
    {
        $this->validate([
            'fileExcel' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            Excel::import(new UsersImport, $this->fileExcel->getRealPath());
            session()->flash('success', 'Data Anggota berhasil di-import/di-upsert via Excel!');
            $this->closeImportModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }
}

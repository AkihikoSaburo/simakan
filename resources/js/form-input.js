// --- 1. LOGIKA INTERAKSI & POSISI DROPDOWN (Event Delegation) ---

// Fungsi Helper untuk mengatur posisi koordinat dropdown fixed secara presisi
function sesuaikanPosisiDropdown(button, menu) {
    const rect = button.getBoundingClientRect();
    menu.style.top = `${rect.bottom + window.scrollY + 4}px`;
    menu.style.left = `${rect.left + window.scrollX}px`;
    menu.style.width = `${rect.width}px`;
}

// Event global untuk mendeteksi klik
document.addEventListener('click', function (e) {
    const button = e.target.closest('.btn-dropdown');
    
    if (button) {
        e.stopPropagation();
        const currentMenu = button.nextElementSibling;

        // Tutup semua dropdown lain yang sedang aktif di layar
        document.querySelectorAll('.menu-dropdown').forEach(menu => {
            if (menu !== currentMenu) menu.classList.add('hidden');
        });

        // Toggle state buka/tutup
        const isHidden = currentMenu.classList.toggle('hidden');

        // Jika dropdown dibuka, hitung posisinya secara dinamis
        if (!isHidden) {
            sesuaikanPosisiDropdown(button, currentMenu);
        }
        return;
    }

    // Menutup semua menu dropdown jika user mengklik sembarang area luar luar tabel
    if (!e.target.closest('.wrapper-bentuk-makanan')) {
        document.querySelectorAll('.menu-dropdown').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});

// Event global mendeteksi perubahan status checkbox
document.addEventListener('change', function (e) {
    if (e.target.closest('.wrapper-bentuk-makanan') && e.target.type === 'checkbox') {
        const wrapper = e.target.closest('.wrapper-bentuk-makanan');
        const label = wrapper.querySelector('.label-dropdown');
        const checkedBoxes = wrapper.querySelectorAll('input[type="checkbox"]:checked');

        if (checkedBoxes.length > 0) {
            const values = Array.from(checkedBoxes).map(cb => cb.value);
            label.textContent = values.join(', ');
            label.classList.remove('text-brand-gray');
            label.classList.add('text-brand-dark', 'font-semibold');
        } else {
            label.textContent = 'Pilih Bentuk Makanan';
            label.classList.remove('text-brand-dark', 'font-semibold');
            label.classList.add('text-brand-gray');
        }
        
        // Atur ulang posisi barangkali teks label melebar tinggi tombolnya berubah
        const button = wrapper.querySelector('.btn-dropdown');
        const menu = button.nextElementSibling;
        if (!menu.classList.contains('hidden')) {
            sesuaikanPosisiDropdown(button, menu);
        }
    }
});

// Otomatis tutup dropdown jika jendela di-scroll agar kotak tidak tertinggal melayang salah posisi
window.addEventListener('scroll', function() {
    document.querySelectorAll('.menu-dropdown').forEach(menu => {
        menu.classList.add('hidden');
    });
}, true);


// --- 2. LOGIKA MANAJEMEN BARIS TABEL (DI-BIND KE WINDOW OBJECT) ---

window.tambahBarisPasien = function() {
    const tbody = document.querySelector('#tabelPasien tbody');
    const totalBaris = tbody.querySelectorAll('.row-pasien').length;
    const nomorBaru = totalBaris + 1;

    const barisBaruHTML = `
        <tr class="hover:bg-brand-light/5 transition-colors row-pasien">
            <td class="py-3 px-4 text-center font-bold text-brand-gray index-nomor">${nomorBaru}</td>
            <td class="py-3 px-3">
                <input type="text" name="nama_pasien[]" required placeholder="Nama Lengkap" 
                    class="w-full bg-brand-snow border border-brand-light text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary">
            </td>
            <td class="py-3 px-3">
                <input type="text" name="no_rm[]" required placeholder="00-00-00" 
                    class="w-full bg-brand-snow border border-brand-light font-mono text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary">
            </td>
            <td class="py-3 px-3">
                <input type="text" name="kamar_kelas[]" required placeholder="Kmr 3 / Klst II" 
                    class="w-full bg-brand-snow border border-brand-light text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary">
            </td>
            <td class="py-3 px-3 relative wrapper-bentuk-makanan">
                <button type="button" 
                    class="btn-dropdown w-full bg-brand-snow border border-brand-light text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary flex items-center justify-between">
                    <span class="label-dropdown truncate text-brand-gray pointer-events-none">Pilih Bentuk Makanan</span>
                    <i class="fa-solid fa-chevron-down text-[10px] text-brand-gray ml-1 pointer-events-none"></i>
                </button>
                <div class="menu-dropdown hidden fixed bg-white border border-brand-light rounded-xl shadow-lg z-50 max-h-48 overflow-y-auto p-2 space-y-1">
                    <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                        <input type="checkbox" name="bentuk_makanan[][]" value="Nasi" class="rounded border-brand-light text-brand-primary focus:ring-brand-primary"> Nasi
                    </label>
                    <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                        <input type="checkbox" name="bentuk_makanan[][]" value="Bubur" class="rounded border-brand-light text-brand-primary focus:ring-brand-primary"> Bubur
                    </label>
                    <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                        <input type="checkbox" name="bentuk_makanan[][]" value="Msk. Cair / Susu" class="rounded border-brand-light text-brand-primary focus:ring-brand-primary"> Msk. Cair / Susu
                    </label>
                    <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                        <input type="checkbox" name="bentuk_makanan[][]" value="Bubur Saring" class="rounded border-brand-light text-brand-primary focus:ring-brand-primary"> Bubur Saring
                    </label>
                    <label class="flex items-center gap-2 px-2 py-1.5 hover:bg-brand-light/40 rounded-lg cursor-pointer text-xs font-medium text-brand-dark">
                        <input type="checkbox" name="bentuk_makanan[][]" value="Sonde" class="rounded border-brand-light text-brand-primary focus:ring-brand-primary"> Sonde
                    </label>
                </div>
            </td>
            <td class="py-3 px-3">
                <input type="text" name="diet[]" placeholder="Contoh: RG (Rendah Garam), DM" 
                    class="w-full bg-brand-snow border border-brand-light text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary">
            </td>
            <td class="py-3 px-3">
                <input type="text" name="keterangan[]" placeholder="Contoh: Tanpa Telur, Alergi" 
                    class="w-full bg-brand-snow border border-brand-light text-brand-dark rounded-lg p-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-primary">
            </td>
            <td class="py-3 px-4 text-center">
                <button type="button" onclick="hapusBarisPasien(this)" class="text-rose-500 hover:text-rose-700 transition-colors text-sm">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        </tr>
    `;

    tbody.insertAdjacentHTML('beforeend', barisBaruHTML);
};

window.hapusBarisPasien = function(button) {
    const baris = button.closest('.row-pasien');
    baris.remove();
    
    const semuaNomor = document.querySelectorAll('.index-nomor');
    semuaNomor.forEach((td, index) => {
        td.textContent = index + 1;
    });
};
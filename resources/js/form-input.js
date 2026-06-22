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
        <x-form.row-pasien-form
            :nomorBaru="${nomorBaru}" />
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
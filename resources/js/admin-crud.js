// File: admin/dashboard/admin-crud.js

// === KONFIGURASI SCHEMA HALAMAN ===
// Note: Kita tambahkan property 'multiple: true' untuk field image di galeri & artikel
const SCHEMAS = {
    galeri: {
        key: 'db_galeri',
        title: 'Manajemen Galeri Kegiatan',
        headers: ['Gambar', 'Caption', 'Tanggal', 'Aksi'],
        fields: [
            // UBAH KE MULTIPLE: TRUE
            { name: 'image', label: 'Upload Foto (Bisa Banyak)', type: 'file', multiple: true },
            { name: 'caption', label: 'Judul Kegiatan', type: 'text' },
            { name: 'date', label: 'Tanggal Upload', type: 'date' }
        ]
    },
    artikel: {
        key: 'db_artikel',
        title: 'Manajemen Artikel & Berita',
        headers: ['Gambar', 'Judul', 'Link', 'Kategori', 'Aksi'],
        fields: [
            { name: 'title', label: 'Judul Artikel', type: 'text' },
            // UBAH KE MULTIPLE: TRUE (Biar artikel juga bisa carousel)
            { name: 'image', label: 'Thumbnail / Slider Gambar', type: 'file', multiple: true }, 
            { name: 'link', label: 'Link Pendaftaran (Opsional)', type: 'text', placeholder: 'https://...' },
            { name: 'category', label: 'Kategori', type: 'select', options: ['Berita', 'Kegiatan', 'Pengumuman', 'Turnamen', 'Kesehatan'] },
            { name: 'author', label: 'Penulis', type: 'text' },
            { name: 'content', label: 'Isi Artikel', type: 'textarea' },
            { name: 'date', label: 'Tanggal Publish', type: 'date' }
        ]
    },
    pengaduan: {
        key: 'db_pengaduan',
        title: 'Data Pengaduan Masuk',
        headers: ['ID Tiket', 'Tanggal', 'Pelapor', 'Judul', 'Status', 'Aksi'],
        allowAdd: false, 
        fields: [
            { name: 'tanggal', label: 'Tanggal Masuk', type: 'text', readOnly: true },
            { name: 'pelapor', label: 'Nama Pelapor', type: 'text', readOnly: true },
            { name: 'bukti', label: 'Bukti Lampiran', type: 'file', readOnly: true }, // Pengaduan biasanya 1 file aja cukup
            { name: 'judul', label: 'Judul Pengaduan', type: 'text', readOnly: true },
            { name: 'deskripsi', label: 'Isi Laporan', type: 'textarea', readOnly: true },
            { name: 'lokasi', label: 'Lokasi', type: 'text', readOnly: true },
            { name: 'status', label: 'Update Status', type: 'select', options: ['Pending', 'Diproses', 'Selesai', 'Ditolak'] },
            { name: 'response', label: 'Tanggapan Admin', type: 'textarea' }
        ]
    },
    proposal: {
        key: 'db_proposal',
        title: 'Data Pengajuan Proposal',
        headers: ['ID', 'Tanggal', 'Pengusul (PIC)', 'Judul', 'Status', 'Aksi'],
        allowAdd: false,
        fields: [
            { name: 'tanggal', label: 'Tanggal Pengajuan', type: 'text', readOnly: true },
            { name: 'pic', label: 'Penanggung Jawab', type: 'text', readOnly: true },
            { name: 'dokumen', label: 'File Proposal (PDF)', type: 'file', readOnly: true },
            { name: 'judul', label: 'Judul Proposal', type: 'text', readOnly: true },
            { name: 'deskripsi', label: 'Ringkasan', type: 'textarea', readOnly: true },
            { name: 'anggaran', label: 'Anggaran', type: 'text', readOnly: true },
            { name: 'status', label: 'Status Approval', type: 'select', options: ['Menunggu', 'Disetujui', 'Ditolak'] },
            { name: 'notes', label: 'Catatan Revisi', type: 'textarea' }
        ]
    },
    pengguna: {
        key: 'admins',
        title: 'Manajemen Admin',
        headers: ['Username', 'Role', 'Aksi'],
        fields: [
            { name: 'username', label: 'Username', type: 'text' },
            { name: 'password', label: 'Password', type: 'text' },
            { name: 'role', label: 'Role', type: 'select', options: ['Admin', 'Super Admin'] }
        ]
    },
    pengaturan: {
        key: 'db_pengaturan',
        title: 'Pengaturan Website',
        headers: ['Setting', 'Value', 'Aksi'],
        allowAdd: true,
        fields: [
            { name: 'setting', label: 'Nama Pengaturan', type: 'text' },
            { name: 'value', label: 'Value', type: 'text' }
        ]
    }
};

// === DATA HELPER ===
const pageId = document.body.dataset.page;
const config = SCHEMAS[pageId];
let editIndex = -1;

function getData() {
    const data = localStorage.getItem(config.key);
    return JSON.parse(data) || [];
}

function getStatusBadge(status) {
    const colors = { 'Selesai': 'done', 'Disetujui': 'done', 'Diproses': 'process', 'Menunggu': 'pending', 'Pending': 'pending', 'Ditolak': 'rejected' };
    return `<span class="status-badge ${colors[status] || ''}">${status}</span>`;
}

// === LOAD TABLE (Updated for Multiple Images) ===
function loadTable() {
    if (!config) return;

    document.getElementById('pageTitle').textContent = config.title;
    if (config.allowAdd === false) {
        const btnAdd = document.getElementById('btnAdd');
        if(btnAdd) btnAdd.style.display = 'none';
    }

    const thead = document.getElementById('tableHeaders');
    thead.innerHTML = config.headers.map(h => `<th>${h}</th>`).join('');

    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';
    const data = getData();

    if(data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="${config.headers.length}" style="text-align:center; padding: 30px; color: #666;">Belum ada data bestie.</td></tr>`;
        return;
    }

    data.forEach((item, idx) => {
        let rowHtml = '';
        
        // Helper buat render thumbnail
        const renderThumb = (imgData) => {
            if (Array.isArray(imgData) && imgData.length > 0) {
                // Kalau array, ambil yg pertama + badge
                const count = imgData.length > 1 ? `<span style="position:absolute; bottom:0; right:0; background:rgba(0,0,0,0.6); color:white; font-size:10px; padding:2px 4px; border-radius:4px 0 4px 0;">+${imgData.length - 1}</span>` : '';
                return `<div style="position:relative; width:50px; height:50px; display:inline-block;">
                            <img src="${imgData[0]}" style="width:100%; height:100%; border-radius:6px; object-fit:cover;">
                            ${count}
                        </div>`;
            } else if (typeof imgData === 'string' && imgData.length > 0) {
                // Legacy string
                return `<img src="${imgData}" style="height:50px; width:50px; border-radius:6px; object-fit:cover;">`;
            }
            return '-';
        };

        if (pageId === 'galeri') {
            rowHtml = `<td>${renderThumb(item.image)}</td><td>${item.caption}</td><td>${item.date}</td>`;
        } 
        else if (pageId === 'artikel') {
            const link = item.link ? `<a href="${item.link}" target="_blank" onclick="event.stopPropagation()" style="color:#A50104; font-weight:600;">Link ↗</a>` : '-';
            rowHtml = `<td>${renderThumb(item.image)}</td><td>${item.title}</td><td>${link}</td><td>${item.category}</td>`;
        }
        else if (pageId === 'pengaduan') {
             rowHtml = `<td>${item.id}</td><td>${item.tanggal}</td><td>${item.pelapor}</td><td>${item.judul}</td><td>${getStatusBadge(item.status)}</td>`;
        }
        else if (pageId === 'proposal') {
             rowHtml = `<td>${item.id}</td><td>${item.tanggal}</td><td>${item.pic}</td><td>${item.judul}</td><td>${getStatusBadge(item.status)}</td>`;
        }
        else if (pageId === 'pengguna') {
             rowHtml = `<td>${item.username}</td><td>${item.role || 'Admin'}</td>`;
        }
        else if (pageId === 'pengaturan') {
            rowHtml = `<td>${item.setting}</td><td>${item.value}</td>`;
        }

        rowHtml += `
            <td>
                <button class="btn-action btn-edit" onclick="event.stopPropagation(); editData(${idx})">Edit</button>
                <button class="btn-action btn-delete" onclick="event.stopPropagation(); deleteData(${idx})">Hapus</button>
            </td>
        `;
        tbody.innerHTML += `<tr onclick="editData(${idx})" style="cursor:pointer;" title="Klik untuk lihat detail">${rowHtml}</tr>`;
    });
}

// === RENDER FORM (Updated for Multiple Upload) ===
function renderForm() {
    const container = document.getElementById('formInputs');
    container.innerHTML = config.fields.map(field => {
        const isDisabled = field.readOnly ? 'disabled style="background:#f3f4f6; cursor: not-allowed;"' : '';
        const label = field.readOnly ? `${field.label} <small>(Read Only)</small>` : field.label;
        const isRequired = (field.name === 'link' || field.readOnly) ? '' : 'required';
        // Cek apakah field support multiple files
        const isMultiple = field.multiple ? 'multiple' : '';

        if (field.type === 'file') {
            let inputHtml = '';
            
            if (field.readOnly) {
                // Read Only view (Existing logic is fine for singular proof)
                inputHtml = `
                    <div id="view_file_${field.name}" style="padding: 10px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <p id="no_file_${field.name}" style="color:#64748b; font-size:13px; margin:0;">Tidak ada file terlampir.</p>
                        <div id="has_file_${field.name}" style="display:none;">
                            <img id="img_view_${field.name}" src="" style="max-width:100%; max-height:200px; border-radius:8px; display:none; margin-bottom:10px;">
                            <a id="link_view_${field.name}" href="#" target="_blank" class="btn-primary" style="display:inline-block; text-decoration:none; font-size:13px; padding:8px 12px;">
                                📄 Download / Lihat File
                            </a>
                        </div>
                    </div>
                `;
            } else {
                // Upload Input (Support Multiple)
                inputHtml = `
                    <div class="file-upload-container">
                        <input type="file" id="inp_${field.name}" class="file-upload-input" accept="image/*" ${isMultiple} onchange="handleFileSelect(this, '${field.name}', ${field.multiple})">
                        <label for="inp_${field.name}" class="file-upload-label">
                            <svg class="upload-icon" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span class="upload-text">${field.multiple ? 'Klik untuk upload banyak foto sekaligus' : 'Klik untuk upload gambar'}</span>
                        </label>
                        
                        <!-- Container Preview (Bisa banyak) -->
                        <div id="preview_container_${field.name}" style="display:flex; flex-wrap:wrap; gap:10px; margin-top:10px;"></div>
                        
                        <!-- Input hidden buat nyimpen string Base64 (JSON string kalau multiple) -->
                        <input type="hidden" id="base64_${field.name}">
                    </div>
                `;
            }
            return `<div class="form-group"><label>${label}</label>${inputHtml}</div>`;
        }

        // ... (Select & Textarea logic same as before) ...
        if (field.type === 'select') {
            return `<div class="form-group"><label>${label}</label><select id="inp_${field.name}" class="form-input" ${isDisabled}>${field.options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}</select></div>`;
        }
        if (field.type === 'textarea') {
            return `<div class="form-group"><label>${label}</label><textarea id="inp_${field.name}" class="form-input" rows="3" ${isDisabled} ${isRequired}></textarea></div>`;
        }
        return `<div class="form-group"><label>${label}</label><input type="${field.type}" id="inp_${field.name}" class="form-input" ${isDisabled} ${isRequired}></div>`;
    }).join('');
}

// === LOGIC FILE UPLOAD (MASSIVE UPDATE) ===
// Array temporary buat nampung file yang lagi diedit/upload
let tempFiles = {}; 

window.handleFileSelect = (input, fieldName, isMultiple) => {
    const files = Array.from(input.files);
    if (files.length === 0) return;

    const container = document.getElementById(`preview_container_${fieldName}`);
    const hiddenInput = document.getElementById(`base64_${fieldName}`);
    
    // Kalau tidak multiple, clear dulu
    if (!isMultiple) {
        container.innerHTML = '';
        tempFiles[fieldName] = []; 
    } else {
        // Kalau multiple, inisialisasi array jika belum ada
        if (!tempFiles[fieldName]) tempFiles[fieldName] = [];
    }

    // Process tiap file
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64 = e.target.result;
            
            // Push ke temp storage
            if (!tempFiles[fieldName]) tempFiles[fieldName] = [];
            tempFiles[fieldName].push(base64);

            // Update Hidden Input (Convert Array to JSON String biar bisa disimpen di localStorage)
            hiddenInput.value = JSON.stringify(tempFiles[fieldName]);

            // Render Preview Card
            const div = document.createElement('div');
            div.className = 'file-preview-box';
            div.style.display = 'flex'; // Force show
            div.innerHTML = `
                <img src="${base64}" class="preview-img">
                <div class="preview-info" style="max-width: 100px;">
                    <div style="font-weight:600; font-size:11px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${file.name}</div>
                </div>
                <button type="button" class="btn-remove-file" onclick="removeSpecificFile('${fieldName}', '${base64}', this)">×</button>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
};

// Hapus 1 file spesifik dari daftar upload
window.removeSpecificFile = (fieldName, base64Data, btnElement) => {
    // Hapus dari temp array
    if (tempFiles[fieldName]) {
        tempFiles[fieldName] = tempFiles[fieldName].filter(f => f !== base64Data);
        
        // Update hidden input
        const hiddenInput = document.getElementById(`base64_${fieldName}`);
        hiddenInput.value = JSON.stringify(tempFiles[fieldName]);
    }
    
    // Hapus elemen visual
    btnElement.parentElement.remove();
};

// === MODAL OPEN (POPULATE DATA) ===
window.editData = (idx) => {
    editIndex = idx;
    openModal(true);
    document.getElementById('modalTitle').textContent = (pageId === 'pengaduan' || pageId === 'proposal') ? 'Detail Data' : 'Edit Data';
    
    const item = getData()[idx];
    tempFiles = {}; // Reset temp

    config.fields.forEach(field => {
        if (field.type === 'file') {
            const rawVal = item[field.name];
            
            if (field.readOnly) {
                // ... (Legacy ReadOnly logic same as before) ...
                const hasFileEl = document.getElementById(`has_file_${field.name}`);
                const noFileEl = document.getElementById(`no_file_${field.name}`);
                const imgView = document.getElementById(`img_view_${field.name}`);
                const linkView = document.getElementById(`link_view_${field.name}`);
                
                if(rawVal) {
                    noFileEl.style.display = 'none';
                    hasFileEl.style.display = 'block';
                    // Cek image simple
                    if (rawVal.startsWith('data:image') || rawVal.includes('placeholder')) {
                        imgView.src = rawVal; imgView.style.display = 'block';
                        linkView.href = rawVal; linkView.textContent = 'Lihat Gambar';
                    } else {
                        imgView.style.display = 'none';
                        linkView.href = rawVal; linkView.textContent = 'Download File';
                    }
                } else {
                    noFileEl.style.display = 'block'; hasFileEl.style.display = 'none';
                }

            } else {
                // Editable Field (Handling Array vs String)
                let fileList = [];
                
                // Parse data lama (bisa jadi JSON string array, atau string tunggal raw)
                try {
                    if (Array.isArray(rawVal)) {
                        fileList = rawVal;
                    } else if (rawVal && rawVal.startsWith('[')) {
                        fileList = JSON.parse(rawVal);
                    } else if (rawVal) {
                        fileList = [rawVal]; // Convert legacy single string to array
                    }
                } catch (e) {
                    if(rawVal) fileList = [rawVal];
                }

                // Simpan ke tempFiles biar sinkron kalau user mau nambah/hapus
                tempFiles[field.name] = fileList;
                document.getElementById(`base64_${field.name}`).value = JSON.stringify(fileList);

                // Render Preview yang sudah ada
                const container = document.getElementById(`preview_container_${field.name}`);
                container.innerHTML = ''; // Clear dulu
                
                fileList.forEach((base64, i) => {
                    const div = document.createElement('div');
                    div.className = 'file-preview-box';
                    div.style.display = 'flex';
                    div.innerHTML = `
                        <img src="${base64}" class="preview-img">
                        <div class="preview-info"><small>Gambar ${i+1}</small></div>
                        <button type="button" class="btn-remove-file" onclick="removeSpecificFile('${field.name}', '${base64}', this)">×</button>
                    `;
                    container.appendChild(div);
                });
            }
        } else {
            // Text/Select Inputs
            const el = document.getElementById(`inp_${field.name}`);
            if(el && item[field.name]) el.value = item[field.name];
        }
    });
};

// === SAVE DATA ===
const crudForm = document.getElementById('crudForm');
if (crudForm) {
    crudForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const data = getData();
        const newItem = {};
        
        config.fields.forEach(field => {
            if(!field.readOnly) {
                if (field.type === 'file') {
                    // Ambil value dari hidden input (format JSON string)
                    const rawJson = document.getElementById(`base64_${field.name}`).value;
                    try {
                        // Simpan sebagai Array Object asli ke localStorage (Bukan string JSON)
                        // Biar pas di frontend gampang di-looping
                        const parsed = JSON.parse(rawJson);
                        newItem[field.name] = parsed; 
                    } catch (err) {
                        // Fallback kalau kosong atau error, simpan array kosong
                        newItem[field.name] = [];
                    }
                } else {
                    newItem[field.name] = document.getElementById(`inp_${field.name}`).value;
                }
            }
        });

        if (editIndex > -1) {
            data[editIndex] = { ...data[editIndex], ...newItem };
        } else {
            if(pageId === 'pengaduan') {
                newItem.id = `CTR-${Date.now()}`;
                newItem.tanggal = new Date().toISOString().split('T')[0];
            }
            data.push(newItem);
        }

        localStorage.setItem(config.key, JSON.stringify(data));
        closeModal();
        loadTable();
    });
}

// === COMMON FUNCTIONS ===
window.openModal = (isEdit = false) => {
    const modal = document.getElementById('crudModal');
    if(modal) modal.style.display = 'flex';
    renderForm();
    
    // Reset Scroll
    const formContainer = document.querySelector('#crudForm'); 
    if(formContainer) formContainer.scrollTop = 0;

    if (!isEdit) {
        document.getElementById('modalTitle').textContent = 'Tambah Data Baru';
        document.getElementById('crudForm').reset();
        tempFiles = {}; // Clear temp uploads
        document.querySelectorAll('[id^="preview_container_"]').forEach(el => el.innerHTML = '');
        editIndex = -1;
    }
};

window.closeModal = () => {
    const modal = document.getElementById('crudModal');
    if(modal) modal.style.display = 'none';
};

// ... (Delete Logic & Event Listeners tetap sama) ...
let indexToDelete = -1;
window.deleteData = (idx) => {
    indexToDelete = idx;
    const modal = document.getElementById('deleteModal');
    if(modal) modal.style.display = 'flex';
};
window.closeDeleteModal = () => {
    const modal = document.getElementById('deleteModal');
    if(modal) modal.style.display = 'none';
    indexToDelete = -1;
};
window.confirmDeleteData = () => {
    if (indexToDelete > -1) {
        const data = getData();
        data.splice(indexToDelete, 1);
        localStorage.setItem(config.key, JSON.stringify(data));
        loadTable();
        closeDeleteModal();
    }
};
window.addEventListener('click', (e) => {
    const crudModal = document.getElementById('crudModal');
    const deleteModal = document.getElementById('deleteModal');
    if (e.target === crudModal) closeModal();
    if (e.target === deleteModal) closeDeleteModal();
});
document.addEventListener('DOMContentLoaded', loadTable);
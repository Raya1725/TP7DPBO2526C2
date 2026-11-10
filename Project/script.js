const SELECTED_CLASS = "selected-cell";

/**
 * Menentukan apakah baris adalah baris data (bukan baris judul).
 * Baris judul dikecualikan karena tidak boleh berubah warna atau
 * menampilkan tombol aksi.
 *
 * @param {HTMLTableRowElement|null} row Baris hasil closest('tr').
 * @returns {boolean} true jika baris data.
 * Kompleksitas: waktu O(1), memori O(1).
 */
function isDataRow(row) {
  return row !== null && row.querySelector("th") === null;
}

/**
 * Menandai satu baris sebagai terpilih dan melepas tanda baris sebelumnya.
 * Hanya satu baris boleh terpilih agar tombol aksi muncul di satu baris saja.
 *
 * @param {HTMLTableRowElement} row Baris yang diklik.
 * Kompleksitas: waktu O(1), memori O(1).
 */
function selectRow(row) {
  const previousRow = document.querySelector(`.${SELECTED_CLASS}`);

  if (previousRow) {
    previousRow.classList.remove(SELECTED_CLASS);
  }

  row.classList.add(SELECTED_CLASS);
}

/**
 * Mengisi setiap field dialog dari sel baris. Field memakai atribut
 * data-column sebagai nomor sel, sehingga satu fungsi melayani semua
 * tabel tanpa kode khusus per halaman.
 *
 * @param {HTMLDialogElement} dialog Dialog update.
 * @param {HTMLTableRowElement} row Baris sumber data.
 * Kompleksitas: waktu O(k), memori O(1), k = jumlah field.
 */
function fillDialog(dialog, row) {
  dialog.querySelectorAll("[data-column]").forEach((field) => {
    field.value = row.cells[Number(field.dataset.column)].textContent.trim();
  });
}

/**
 * Membuka dialog update yang terisi data baris tombol tersebut.
 * Id dialog dibaca dari atribut tombol agar tiap halaman bisa
 * punya dialog sendiri.
 *
 * @param {HTMLButtonElement} button Tombol dengan atribut data-update-dialog.
 * Kompleksitas: waktu O(k), memori O(1).
 */
function openUpdateDialog(button) {
  const dialog = document.getElementById(button.dataset.updateDialog);

  fillDialog(dialog, button.closest("tr"));
  dialog.showModal();
}

// Satu listener di document (event delegation) agar semua tabel dan
// dialog tertangani, termasuk elemen yang dirender belakangan.
document.addEventListener("click", (event) => {
  const updateButton = event.target.closest("[data-update-dialog]");

  if (updateButton) {
    openUpdateDialog(updateButton);
    return;
  }

  const closeButton = event.target.closest("[data-close-dialog]");

  if (closeButton) {
    closeButton.closest("dialog").close();
    return;
  }

  const row = event.target.closest("tr");

  if (isDataRow(row)) {
    selectRow(row);
  }
});

// Form dengan atribut data-confirm meminta konfirmasi sebelum dikirim,
// dipakai untuk mencegah penghapusan yang tidak disengaja.
document.addEventListener("submit", (event) => {
  const message = event.target.dataset.confirm;

  if (message && !confirm(message)) {
    event.preventDefault();
  }
});

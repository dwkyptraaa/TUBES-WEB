<div id="errorModal" class="modal">
    <div class="modal-content">
        <h2 id="modalMessage">Pesan Error atau Sukses</h2>
        <p id="modalDescription">Deskripsi pesan kesalahan atau keberhasilan</p>
        <button id="closeBtn" class="modal-btn">Tutup</button>
    </div>
</div>

<style>
    /* Modal Background */
    .modal {
        display: none; /* Modal hidden by default */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
        overflow: auto;
        padding-top: 0px;
        justify-content: center;
        align-items: center;
        text-align: center;
        transition: opacity 0.3s ease-in-out;
    }

    /* Modal Content Box */
    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        animation: slideIn 0.5s ease-in-out;
    }

    /* Title styling */
    .modal-content h2 {
        color: #D32F2F;
        font-size: 1.5em;
        margin-bottom: 20px;
    }

    /* Modal Button */
    .modal-btn {
        background-color: #2196F3;
        color: white;
        padding: 10px 20px;
        font-size: 1.2em;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-btn:hover {
        background-color: #0b7dda;
    }

    /* Animation for sliding in the modal */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- JavaScript untuk Menutup Modal -->
<script>
    // Menutup modal ketika tombol 'Tutup' ditekan
    document.getElementById('closeBtn').onclick = function() {
        document.getElementById('errorModal').style.display = 'none';
    };

    // Menutup modal jika pengguna mengklik di luar modal
    window.onclick = function(event) {
        var modal = document.getElementById('errorModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
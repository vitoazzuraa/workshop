<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
            Sistem Koleksi Buku &copy; {{ date('Y') }}
        </span>
    </div>
</footer>

{{-- Purple Admin JS (local) --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>

{{-- DataTables (CDN — not in Purple template) --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

{{-- Select2 (local Purple) --}}
<script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>

{{-- SweetAlert2 & Axios (CDN) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // CSRF setup untuk Ajax dan Axios
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

    // Helper: ubah button jadi spinner, return fungsi restore
    function btnLoading(btn) {
        const orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
        return function restore() {
            btn.disabled = false;
            btn.innerHTML = orig;
        };
    }

    // Helper: submit form dengan validasi dulu, lalu spinner
    function submitWithSpinner(formId, btnId) {
        const form = document.getElementById(formId);
        const btn  = document.getElementById(btnId);
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        btnLoading(btn);
        form.submit();
    }
</script>

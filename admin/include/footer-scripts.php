<!-- <script src="./pos-system/vendor/jquery/jquery-3.7.1.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- <script src="./vendor/bootstrap/js/bootstrap.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="./assets/js/scripts-1.1.js"></script>

<!-- Data Tables -->
<script src="./node_modules/datatables.net/js/dataTables.min.js"></script>
<script src="./node_modules/datatables.net/js/dataTables.min.js"></script>
<script src="./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script src="./node_modules/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="./node_modules/jszip/dist/jszip.min.js"></script>
<script src="./node_modules/pdfmake/build/pdfmake.min.js"></script>
<script src="./node_modules/pdfmake/build/vfs_fonts.js"></script>
<script src="./node_modules/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="./node_modules/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="./node_modules/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<script src="./node_modules/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="./node_modules/select2/dist/js/select2.min.js"></script>

<script>
// Initialize the agent at application startup.
const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
    .then(FingerprintJS => FingerprintJS.load())

// Get the visitor identifier when you need it.
fpPromise
    .then(fp => fp.get())
    .then(result => {
        // This is the visitor identifier:
        const visitorId = result.visitorId
        console.log(visitorId)
        $('#deviceFingerPrint').val(visitorId)
        getDeviceApproval('<?php echo $session_student_number; ?>', '<?php echo $session_user_level; ?>', visitorId)
    })
</script>
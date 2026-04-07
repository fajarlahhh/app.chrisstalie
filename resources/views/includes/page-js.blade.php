<!-- ================== BEGIN core-js ================== -->
<script src="{{ config('app.aset_cdn_url') }}/assets/js/vendor.min.js"></script>
<script src="{{ config('app.aset_cdn_url') }}/assets/js/app.min.js"></script>
<!-- ================== END core-js ================== -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.10.5/dist/autoNumeric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select-picker@0.3.2/dist/picker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

@stack('scripts')

<script>
    function deleteOrCancel(id) {
        $('.action').toggle();
        $('.delete' + id).toggle();
    }

    function numberFormat(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep,
            dec = typeof dec_point === 'undefined' ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    
    $(function() {
        $('body').on('keydown', '.bs-searchbox .form-control', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
        // ------------------------------
    });
</script>
<script>
    function openMyDocuments(fromInputId, documentType) {
        documentType = typeof documentType === 'undefined' ? '' : '?custom_type=' + documentType;
        window.open(
            '{{ meUrl('documents/for/popup/{input_id}', ['input_id' => '{input_id}']) }}'.replace('{input_id}', fromInputId) + documentType,
            '{{ trans('pages.my_documents_title') }}',
            'width=900,height=480'
        );
    }
    function processSelectedFile(file_url, input_id) {
        $('#' + input_id).val(file_url);
    }
    $(function () {
        $('.image-from-documents').on('click', function () {
            var $this = $(this);
            var id = $this.is('input,textarea,select') ? $this.attr('id') : $this.attr('data-input-id');
            openMyDocuments(id, 'images');
        });
        $('.file-from-documents').on('click', function () {
            var $this = $(this);
            var id = $this.is('input,textarea,select') ? $this.attr('id') : $this.attr('data-input-id');
            openMyDocuments(id, $this.attr('data-document-types'));
        });
    });
</script>
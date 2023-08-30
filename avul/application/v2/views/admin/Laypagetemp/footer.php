
        <div class="sidenav-overlay"></div>
        <div class="drag-target"></div>
        <!-- <footer class="footer footer-static footer-light navbar-border navbar-shadow">
            <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2"><span class="float-md-left d-block d-md-inline-block">Copyright &copy; <?php echo date('Y'); ?> <a class="text-bold-800 grey darken-2" href="https://1.envato.market/modern_admin" target="_blank">Datasense Technologies</a></span><span id="scroll-top"></span></span></p>
        </footer> -->

        <script src="<?php echo BASE_URL; ?>app-assets/js/jquery-3.4.1.slim.min.js"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/vendors.min.js"></script>

        <script src="<?php echo BASE_URL; ?>app-assets/js/core/app-menu.min.js"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/js/core/app.min.js"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/js/scripts/customizer.min.js"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/js/scripts/footer.min.js"></script>
        <!-- <script src="<?php echo BASE_URL; ?>app-assets/js/scripts/forms/wizard-steps.min.js"></script> -->

        <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/editors/codemirror/lib/codemirror.js"></script>

        <script src="<?php echo BASE_URL; ?>app-assets/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/toastr/js/toastr.min.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/toastr/js/toastr-init.js" type="text/javascript"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/js/select2.full.js" type="text/javascript"></script>

        <!-- custome_js -->
        <script src="<?php echo BASE_URL; ?>assets/ajax/avul-page.js"></script>
        <script src="<?php echo BASE_URL; ?>assets/ajax/avul-custom.js"></script>
        <!-- <script src="<?php echo BASE_URL; ?>assets/ajax/new_product.js"></script> -->
        <script src="<?php echo BASE_URL; ?>app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
        <script src="<?php echo BASE_URL; ?>app-assets/js/bootstrap-datepicker.js"></script>

        <script src="<?php echo BASE_URL; ?>assets/ajax/map.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAGTzqSJC4qGywi6Gfn-Ev9kitodUq-AIQ&amp;libraries=places"></script>

        <!-- text editer -->
        <link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>assets/trumbowyg/trumbowyg.css">
        <script type="text/javascript" src="<?php echo BASE_URL; ?>assets/trumbowyg/trumbowyg.js"></script>

        <script>
            // Doing this in a loaded JS file is better, I put this here for simplicity
            $('.editor').trumbowyg(
            {   
                btns: [
                    ['viewHTML'],
                    ['undo', 'redo'],
                    ['formatting'],
                    ['strong', 'em', 'del'],
                    ['superscript', 'subscript'],
                    ['link'],
                    ['insertImage'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen'],
                    ['fontfamily'],
                ],
            });
        </script>
    </body>
</html>
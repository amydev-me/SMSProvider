const VuePagination = resolve => require(['../core/VuePagination'], resolve);

module.exports = {
    components:{VuePagination},
    data: function () {
        return {
            articles:[],
            is_edit:false,
            article: {
                id:null,
                title:null,
                questions:null,
                answers: null
            },
            options: [
                { text: 'Active', value: false },
                { text: 'Inactive', value: true }
            ]
        }
    },
    methods: {

    },
    mounted() {
        tinymce.init({
            selector: "textarea",
            height: 300,
            plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen link template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        });
    }
}
var app = new Vue({
   el: '#app',
   data: {
       updateOrCreate: "Cadastrar",
       errorMsg: "",
       successMsg: "",
       showFormModal: false,
       news: [],
       newNews: {title: "", description: "", image:""},
       currentPost: {},
       extension: "",
       selectedFile: null
   },
   mounted: function() {
       this.getAllNews();
   },
    methods:{
        getAllNews() {
            axios.get('http://campotv.test/app/action/news.php?action=index').then( response => {
                console.log(response)
                if(response.data.status)
                    app.news = response.data.data;
                else
                    app.errorMsg = response.data.message;
            });
        },
        addNews() {
            var formData = app.toFormData(app.newNews);

            axios.post('http://campotv.test/app/action/news.php?action=create', formData).then( response => {

                app.newNews = {title: "", description: "", image:""};

                if(response.data.status) {
                    app.getAllNews();
                    app.successMsg = response.data.message;
                } else
                    app.errorMsg = response.data.message;
            });
        },
        updateNews() {
            var formData = app.toFormData(app.currentPost);

            axios.post('http://campotv.local/process.php?action=update', formData).then( response => {

                app.currentPost = {};

                if(response.data.status) {
                    app.successMsg = response.data.message;
                    app.getAllNews();
                } else
                    app.errorMsg = response.data.message;
            });
        },
        deleteNews(){
            var formData = app.toFormData(app.currentPost);

            axios.post('http://campotv.local/process.php?action=delete', formData).then( response => {

                app.currentPost = {};

                if(response.data.status) {
                    app.successMsg = response.data.message;
                    app.getAllNews();
                } else
                    app.errorMsg = response.data.message;
            });
        },
        toFormData ( obj ) {
            var fd = new FormData();

            for(var i in obj)
                fd.append(i, obj[i]);

            return fd;
        },
        selectPost ( post ) {
            app.currentPost = post;
        },
        exportXmlOrJson(action, withImage ) {
            var url = 'http://campotv.local/process.php?action=' + action + '&image=' + withImage;
            console.log(action)
            app.extension = action == 'exportXml' ? 'xml' : 'json';

            axios({

                url: url,

                method: 'GET',

                responseType: 'blob',

            }).then( response => {

                var fileURL = window.URL.createObjectURL(new Blob([response.data]));

                var fileLink = document.createElement('a');

                fileLink.href = fileURL;

                fileExtension = app.extension;

                fileLink.setAttribute('download', 'news.'+fileExtension);

                document.body.appendChild(fileLink);

                fileLink.click();

            });
        },
        getFileUpload( event ) {
            app.selectedFile = event.target.files[0];
            app.newNews.image = app.selectedFile.name;
        }
    }
});


<template>
    <div class="_container">
      <b>{{msg}}</b>
      <form @submit.prevent="saveData">
        <input v-model="form.title" class="test" autocomplete="off" type="text" aria-label="WRITE SHIT" @keydown="form.errors.clear('name')">
        <button type="submit">DO SHIT!</button>        
       <span v-if="form.errors.has('name')" v-text="form.errors"></span>  
      </form>
      <h2>Список книг</h2>
      <paginate
        name="books_list"
        :list="books"
        :per="5"
        >
        <table class=" _table">
            <tr>
                <th>Название</th>
                <th>Автор</th>
                <th>Год публикации</th>
                <th>Описание</th>
            </tr>
            <tr v-for="book in paginated('books_list')">
                <td class="">{{book.name}}</td>
                <td class="">{{book.author}}</td>
                <td class="">{{book.publish_year}}</td>
                <td class="">{{book.description}}</td>
            </tr>
        </table>
        </paginate>
<paginate-links for="books_list"
  :show-step-links="true"

></paginate-links>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                paginate: ['books_list'],
                isAdmin: false,
                user: null,
                authors:'',
                books:'',
                category:'',
                form: new Form({
                    title: '',
                })
            }
        },
        methods:{
            getAuthor(){
                axios.get('/api/author').then((res)=>{
                    this.authors = res.data
                }).catch((error) => {
                    //console.log(error)
                })
            },
            getCategory(){
                axios.get('/api/category').then((res)=>{
                    this.category = res.data
                }).catch((error) => {
                    //console.log(error)
                })
            },
            getBooks(){
                axios.get('/api/books').then((res)=>{
                    this.books = res.data
                }).catch((error) => {
                    //console.log(error)
                })
            },
            getAllBooks(){
                axios.get('/api/books').then((res)=>{
                    this.books = res.data
                }).catch((error) => {
                    //console.log(error)
                })
            },
            getBooks(){
                axios.get('/api/books').then((res)=>{
                    this.books = res.data
                }).catch((error) => {
                    //console.log(error)
                })
            },
            saveData(){
                let data = new FormData();
                data.append("name", this.form.title)
                axios.post('/api/author', data).then((res) => {
                 this.form.reset()
                 this.getData()
                }).catch((error) => {
                    console.log(error)
                    this.form.errors.record(error.response.data.errors)
                })
            }
        },
        props: {
            msg: String
        },
        mounted() {
            console.log('Component mounted.')
            axios.get('/api/user').then((res)=>{
                this.user = res.data
                this.isAdmin = res.data['is_admin']
            }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
            this.getAllBooks()
        }
    }
</script>

<style>
    .test{
        border: 1px solid #000;
    }
</style>

<template>
    <div class="_container">

      <h1>Список книг</h1>
      <input type="text" v-model="search" placeholder="Поиск (автор, название)" class="search">
      <paginate                
        ref="paginator"
        name="books_list"
        v-if="this.books.length"
        :list="books"
        :per="5"
        >
        <table class=" _table">
            <tr>
                <th class="sort__key" v-on:click="sortTable('name')">Название</th>
                <th>Обложка</th>
                <th class="sort__key" v-on:click="sortTable('author')">Автор</th>
                <th class="sort__key" v-on:click="sortTable('publish_year')">Год публикации</th>
                <th>Описание</th>
                <th class="sort__key" v-on:click="sortTable('category')">Категория</th>
            </tr>
            <tr v-for="book in paginated('books_list')">
                <td>{{book.name}}</td>
                <td id="cover">
                    <img :src="book.cover" alt="">
                </td>
                <td>{{book.author}}</td>
                <td>{{book.publish_year}}</td>
                <td>{{book.description}}</td>
                <td>{{book.category}}</td>
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
                // необходима для пагинации
                paginate: ['books_list'],
                is_admin: false,
                user: null,
                authors:'',
                books:[],
                tempBooks: false,
                category:'',
                // направление сортировки (по убыв, по возраст)
                sort_dir: false,
                search: ''
            }
        },
        methods:{
            sortByKey(array, key, dir){
                return array.sort(function(a, b) {
                    var x = a[key]; var y = b[key];
                    if (dir)
                        return ((x > y) ? -1 : ((x < y) ? 1 : 0));
                    return ((x < y) ? -1 : ((x > y) ? 1 : 0));    
                });
            },
            sortTable(key){
                this.books_list = this.sortByKey(this.books, key, this.sort_dir);
                this.sort_dir = !this.sort_dir
            },
            getAuthor(){
                axios.get('/api/author').then((res)=>{
                    this.authors = res.data;
                    // Заменяем id внешних ключей книг на нормальный текст
                    this.books.forEach(function(book, index, arr) {                          
                        res.data.forEach(function(author) {
                            if (author['id'] == book['author']){
                                arr[index]['author'] = author['name'];
                            }
                        });                        
                    });
                }).catch((error) => {
                })
            },
            getCategory(){
                axios.get('/api/category').then((res)=>{
                    this.category = res.data
                    // Заменяем id внешних ключей книг на нормальный текст
                    this.books.forEach(function(book, index, arr) {
                        res.data.forEach(function(cat) {
                            if (cat['id'] == book['category']){
                                arr[index]['category'] = cat['name'];
                            }
                        });
                    });
                }).catch((error) => {
                })
            },
            getBooks(){
                axios.get('/api/books').then((res)=>{
                    let data = res.data;
                    data.forEach(function(book, index, arr) {
                        if (book['hidden']){                            
                            arr = arr.splice(index, 1);
                        } 
                    });

                    this.books = data;
                    
                }).catch((error) => {
                })
            }
        },
        watch: {
            search: function() {
                if (!this.tempBooks){
                    this.tempBooks = this.books 
                }        
                let authors = [];     
                if (this.search != '' && this.search) {
                    this.books = this.tempBooks.filter((item) => {
                    return item.name
                        .toUpperCase()
                        .includes(this.search.toUpperCase())
                    })
                    authors = this.tempBooks.filter((item) => {
                        return item.author
                            .toUpperCase()
                            .includes(this.search.toUpperCase())
                        })
                       
                    this.books = authors.concat(this.books);              
                    if (!this.books){
                        this.books = this.tempBooks
                    }
                } else {
                    this.books = this.tempBooks
                    this.books_list = this.tempBooks
                }
            },
        },
        mounted() {
            axios.get('/api/user').then((res)=>{
                this.user = res.data
                this.is_admin = res.data['is_admin']
            }).catch((error) =>{
                this.errors = error.response.data.errors;
            })
            this.getBooks();
            this.getAuthor();
            this.getCategory();
        }
    }
</script>

<template>
    <div v-if="user" class="_container">
        <h1>Панель управления</h1>
        <div class="user">        
          <b>Email:</b> {{user.email}}<br>
          <b>Имя:</b> {{user.name}} <br>
          <button @click.prevent="logout" class="subimt">Выйти</button>
        </div>   
        <div class="books">
          <h2>Список книг</h2>
          <table class="books__table _table">
            <tr>
              <th>Название книги</th>
              <th>Автор</th>
              <th>Описание</th>
            </tr>
            <tr v-for="book in books" :key="book.id">
              <td class="books__name">{{book.name}}</td>
              <td class="books__author">{{book.author}}</td>
              <td class="books__year">{{book.publish_year}}</td>
              <td class="books__description">{{book.description}}</td>
              <td class="books__description"><button class="_btn">Удалить</button></td>
              <td class="books__description"><button class="_btn">Редактировать</button></td>
            </tr>
          </table>
        </div>     

        <div v-if="is_admin" class="">
          <h2>Управление разделами</h2>
            <table class=" _table">
              <tr>
                <th>Категория</th>
                <th>Описание</th>
              </tr>
              <tr v-for="cat in categories" :key="cat.id">
                <td class="">{{cat.name}}</td>
                <td class="">{{cat.description}}</td>
                <td class=""><button class="_btn">Удалить</button></td>
                <td class=""><button class="_btn">Редактировать</button></td>
              </tr>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            books: '',
            is_admin: '',
            categories: '',
            user: null
        }
    },
    methods:{
        getAllBooks(){
            axios.get('/api/books').then((res)=>{
                if (this.is_admin){
                  this.books = res.data;
                  return;
                } 
                const books = [];
                res.data.forEach(el => {
                  if(el['added_by'] == this.user['id']){                    
                    books.push(el);
                    this.books = books;                    
                  }
                });                 
            }).catch((error) => {
                //console.log(error)
            })
        },
        logout(){
            axios.post('/api/logout').then(()=>{
                this.$router.push({ name: "Home"})
            }).catch((error) =>{
              this.errors = error.response.data.errors;
            })
        }
    },
    mounted(){
        axios.get('/api/user').then((res)=>{
          this.user = res.data;
          this.is_admin = res.data['is_admin']
        }).catch((error) =>{
          this.errors = error.response.data.errors;
        })
        axios.get('/api/category').then((res)=>{
          this.categories = res.data
        }).catch((error) =>{
          this.errors = error.response.data.errors;
        })
        this.getAllBooks()
    }
}
</script>

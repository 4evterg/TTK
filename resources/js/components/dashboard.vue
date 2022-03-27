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
          <table class="books__table _table _table__editable">
            <tr>
              <th>Название книги</th>
              <th>Автор</th>
              <th>Год публикации</th>
              <th>Описание</th>
              <th>Категория</th>
              <th>Скрыто</th>
            </tr>
            <tr v-for="book in tables['books']" :key="book.id">
              <td id="name">{{book.name}}</td>
              <td id="author">{{book.author}}</td>
              <td id="publish_year">{{book.publish_year}}</td>
              <td id="description">{{book.description}}</td>
              <td id="category">{{book.category}}</td>
              <td id="hidden">{{book.hidden}}</td>
              <td class="books__delete">
                <button class="_btn" v-on:click="deleteElement(book, 'books')">Удалить</button>
              </td>
              <td class="books__edit">
                  <button class="_btn" 
                  v-on:click="editElement('books', book, $event, edited['books'][book.id] ? edited['books'][book.id] : false)">
                    Редактировать
                  </button>
              </td>
            </tr>
            <tr>
              <td id="name"></td>
              <td id="author"></td>
              <td id="publish_year"></td>
              <td id="description"></td>
              <td id="category"></td>
              <td id="hidden"></td>
              <td>
                <button class="_btn addBtn" v-on:click="addElement($event, 'books')">
                  + Добавить
                </button>
              </td>
            </tr>
          </table>
        </div>     
        
        <div v-if="is_admin" class="">
          <h2>Управление разделами</h2>
            <table class=" _table">
              <tr>
                <th>Категория</th>
                <th>Описание</th>
                <th>Скрыт</th>
              </tr>
              <tr v-for="cat in tables['category']" :key="cat.id">
                <td class="" id="name">{{cat.name}}</td>
                <td class="" id="description">{{cat.description}}</td>
                <td class="" id="hidden">{{cat.hidden}}</td>
                <td class="books__delete">
                  <button class="_btn" v-on:click="deleteElement(cat, 'category')">Удалить</button>
                </td>
                <td class="books__edit">
                    <button class="_btn" 
                    v-on:click="editElement('category', cat, $event, edited['category'][cat.id] ? edited['category'][cat.id] : false)">
                      Редактировать
                    </button>
                </td>
              </tr>
              <tr>
                <td class="" id="name"></td>
                <td class="" id="description"></td>
                <td class="" id="hidden"></td>
                <td>
                  <button class="_btn addBtn" v-on:click="addElement($event, 'category')">
                    + Добавить
                  </button>
                </td>
              </tr>
            </table>

          <h2>Управление авторами</h2>
            <table class=" _table">
              <tr>
                <th>Имя</th>
                <th>Страна</th>
                <th>Описание</th>
                <th>Скрыт</th>
              </tr>
              <tr v-for="author in tables['author']" :key="author.id">
                <td class="" id="name">{{author.name}}</td>
                <td class="" id="country">{{author.country}}</td>
                <td class="" id="comment">{{author.comment}}</td>
                <td class="books__delete">
                  <button class="_btn" v-on:click="deleteElement(author, 'author')">Удалить</button>
                </td>
                <td class="books__edit">
                    <button class="_btn" 
                    v-on:click="editElement('author', author, $event, edited['author'][author.id] ? edited['author'][author.id] : false)">
                      Редактировать
                    </button>
                </td>
              </tr>
              <tr>
                <td class="" id="name"></td>
                <td class="" id="country"></td>
                <td class="" id="comment"></td>
                <td>
                  <button class="_btn addBtn" v-on:click="addElement($event, 'author')">
                    + Добавить
                  </button>
                </td>
              </tr>
            </table>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
          is_admin: '',
          category: '',
          user: null,
          tables: {
            books: '',
            category: '',
            author: ''
          },            
          add_check:{
            books: false,
            category: false,
            author: false
          },    
          edited: {
            books: [],
            category: [],
            author: [],
          },
          fields:{
            books: {
              name: '',
              author: '',
              publish_year: '',
              description: '',
              category: '',
              hidden: ''
            },
            category: {
              name: '',
              description: '',
              hidden: ''
            },
            author: {
              name: '',
              country: '',
              comment: '',
            }
          },          
        }
    },
    methods:{
        test(){
          console.log('test');
        },
        getAll(type){
            axios.get(`/api/${type}`).then((res)=>{
                let result = res.data;
                if (type == 'author'){                                     
                  this.tables['books'].forEach(function(book, index, arr) {                  
                    result.forEach(function(author) {
                            if (author['id'] == book['author']){
                                arr[index]['author'] = author['name'];
                            }
                        });                        
                    });
                }
                if (type == 'category'){
                  this.tables['books'].forEach(function(book, index, arr) {
                    result.forEach(function(cat) {
                            if (cat['id'] == book['category']){
                                arr[index]['category'] = cat['name'];
                            }
                        });
                  });
                }
                if (this.is_admin){                  
                  this.tables[type] = result;
                  return;
                } 
                const books = [];
                result.forEach(el => {
                  if(el['added_by'] == this.user['id']){                    
                    books.push(el);
                    this.tables[type] = books;                    
                  }
                });                 
            }).catch((error) => {
                //console.log(error)
            })
        },
        editElement(type, table, event, state){
          let row = event.target.parentElement.parentElement;
          event.target.innerText = "Сохранить";

          if (state){
            event.target.innerText = "Редактировать";
            let elements = this.fields[type];

            for (let key in elements) {
              elements[key] = row.querySelector("#"+key).innerText;
            } 
            this.updateElement(table, elements, type);
          }

          for (let key in this.fields[type]) {
            let node = row.querySelector("#"+key);
            node.contentEditable = !state;
          }  
          this.edited[type].push('wtf'); 
          this.edited[type].pop(); 
          this.edited[type][table.id] = !state; 
        },
        deleteElement(e, type){
          let data = new FormData();
          data.append('_method', 'DELETE');
          axios.post(`/api/${type}/` + e.id, data).then((res) =>{
              this.tables[type] = res.data;
          }).catch((error) => {
              this.form.errors.record(error.response.data.errors);
          })
        },
        updateElement(e, elements, type){
            let data = new FormData();
            data.append('_method', 'PATCH');
            
            for (let key in elements) {
              data.append(key, elements[key]);
            }         

            axios.post(`/api/${type}/`+e.id, data).catch((error) => {
              this.form.errors.record(error.response.data.errors)
            })
        },
        addElement(event, type){
          console.log(event + " " + type)
          this.add_check[type] = !this.add_check[type];  
          event.target.innerText = "Сохранить";        

          let row = event.target.parentElement.parentElement;

          for (let key in this.fields[type]) {
            let node = row.querySelector("#"+key);
            node.contentEditable = this.add_check[type];
          }  
          
          if (!this.add_check[type]){   
            event.target.innerText = "+ Добавить";         
            let elements = this.fields[type];

            for (let key in elements) {
              elements[key] = row.querySelector("#"+key).innerText;
              row.querySelector("#"+key).innerText = '';
            } 
            let data = new FormData();

            for (let key in elements) {
              data.append(key, elements[key]);
            }  
            axios.post(`/api/${type}`, data).then((res) => {
              //this.form.reset()
              this.getAll(type)
            }).catch((error) => {
                console.log(error)
               // this.form.errors.record(error.response.data.errors)
            })
          }
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
        for (let key in this.tables) {
          this.getAll(key)
        }
    }
}
</script>

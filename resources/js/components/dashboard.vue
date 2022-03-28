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

            <!-- id = полю массива -->
            <tr v-for="book in tables['books']" :key="book.id">
              <td id="name" class="error">{{book.name}}</td>
              <td id="author">{{book.author}}</td>
              <td id="publish_year">{{book.publish_year}}</td>
              <td id="description">{{book.description}}</td>
              <td id="category">{{book.category}}</td>
              <!-- <td id="img"><img :src="dataUrl(book.cover)"></td> -->
              <td id="hidden">{{book.hidden}}</td>
              <td class="books__delete">
                <button class="_btn" v-on:click="deleteElement(book, 'books')">Удалить</button>
              </td>
              <td class="books__edit">
                  <button class="_btn editBtn" 
                  v-on:click="editElement('books', book, $event, edited['books'][book.id] ? edited['books'][book.id] : false)">
                    Редактировать
                  </button>
              </td>
            </tr>
            <tr>
              <!-- id = полю массива -->
              <td id="name" class="error"></td>
              <td id="author"></td>
              <td id="publish_year"></td>
              <td id="description"></td>
              <td id="category"></td>
              <td id="hidden"></td>
             <!-- <td id="cover"><input id="cover_img" type="file" accept=".jpg, .jpeg, .png"></td> -->
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
                <!-- id = полю массива -->
                <td id="name" class="error">{{cat.name}}</td>
                <td id="description">{{cat.description}}</td>
                <td id="hidden">{{cat.hidden}}</td>
                <td class="books__delete">
                  <button class="_btn" v-on:click="deleteElement(cat, 'category')">Удалить</button>
                </td>
                <td class="books__edit">
                    <button class="_btn editBtn" 
                    v-on:click="editElement('category', cat, $event, edited['category'][cat.id] ? edited['category'][cat.id] : false)">
                      Редактировать
                    </button>
                </td>
              </tr>
              <tr>
                <td id="name" class="error"></td>
                <td id="description"></td>
                <td id="hidden"></td>
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
                <!-- id = полю массива -->
                <td id="name" class="error">{{author.name}}</td>
                <td id="country">{{author.country}}</td>
                <td id="comment">{{author.comment}}</td>
                <td class="books__delete">
                  <button class="_btn" v-on:click="deleteElement(author, 'author')">Удалить</button>
                </td>
                <td class="books__edit">
                    <button class="_btn editBtn" 
                    v-on:click="editElement('author', author, $event, edited['author'][author.id] ? edited['author'][author.id] : false)">
                      Редактировать
                    </button>
                </td>
              </tr>
              <tr>
                <td id="name" class="error"></td>
                <td id="country"></td>
                <td id="comment"></td>
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
            category: '',
            author: '',
            books: ''
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
              // cover: ''
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
        getAll(type){
            axios.get(`/api/${type}`).then((res)=>{
                let result = res.data;
                
                // Заменяем id внешних ключей на нормальный текст
                if (type == 'books'){                                                        
                  result.forEach((book, index, arr) => {                                   
                      this.tables['author'].forEach(author  => {
                        if (author['id'] == book['author']){
                            arr[index]['author'] = author['name'];
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
            })
        },
        // @param {string} type - имя таблицы с которой работаем.
        // @param {object} table - строка таблицы, обьект.
        // @param {boolean} state - была ли кнопка нажата до этого
        editElement(type, table, event, state){
          let row = event.target.parentElement.parentElement;
          event.target.innerText = "Сохранить";

          if (state){            
            let elements = this.fields[type];

            for (let key in elements) {
              elements[key] = row.querySelector("#"+key).innerText;
            }               
            
            if (elements['name'].replace(/\s/g, '').length == 0 && elements['name'].replace(/\s/g, '')  == ""){
              console.log("DF")
              let elem = row.querySelector("#name");
              elem.classList.add("active")
              setTimeout(() => row.querySelector("#name").classList.remove("active"), 2000);
              return
            }
            event.target.innerText = "Редактировать";
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
          this.add_check[type] = !this.add_check[type];  
          event.target.innerText = "Сохранить";        

          let row = event.target.parentElement.parentElement;
          
          if (!this.add_check[type]){  
            let elements = this.fields[type];        
           
            for (let key in elements) {
              elements[key] = row.querySelector("#"+key).innerText;
              row.querySelector("#"+key).innerText = '';            
            } 
            if (elements['name'].replace(/\s/g, '').length == 0 && elements['name'].replace(/\s/g, '')  == ""){
              console.log("DF")
              let elem = row.querySelector("#name");
              elem.classList.add("active")
              setTimeout(() => row.querySelector("#name").classList.remove("active"), 2000);
              return
            }

            event.target.innerText = "+ Добавить"; 
            let data = new FormData();

            for (let key in elements) {
              data.append(key, elements[key]);
            }  
            axios.post(`/api/${type}`, data).then((res) => {
              this.getAll(type)
            }).catch((error) => {
               // this.form.errors.record(error.response.data.errors)
            })
          }
          for (let key in this.fields[type]) {
            let node = row.querySelector("#"+key);
            node.contentEditable = this.add_check[type];
          }  
        },
        logout(){
          axios.post('/api/logout').then(()=>{
              this.$router.push({ name: "Home"})
          }).catch((error) =>{
            this.errors = error.response.data.errors;
          })
        },
        // dataUrl(image){
        //     console.log(image)
        //     return 'data:image/jpeg;base64,' + btoa(
        //         new Uint8Array(image)
        //         .reduce((data, byte) => data + String.fromCharCode(byte), '')
        //     );
        // }
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

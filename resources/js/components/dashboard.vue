<template>
    <div v-if="user" class="_container">
        <h1>Панель управления</h1>
        <div class="user">        
          <b>Email:</b> {{user.email}}<br>
          <b>Имя:</b> {{user.name}} <br>
          <button @click.prevent="logout" class="subimt">Выйти</button>
        </div>   
        <div class="books" >
          <h2>Список книг</h2>          
          <table class="books__table _table _table__editable">
            <tr>
              <th>Название книги</th>
              <th>Автор</th>
              <th>Год публикации</th>
              <th>Описание</th>
              <th>Категория</th>
              <th>Обложка</th>
              <th>Скрыто</th>
            </tr>

            <!-- id = полю массива -->
            <tr v-for="book in tables['books']" :key="book.id">
              <td id="name" class="error">{{book.name}}</td>
              <td id="author">{{book.author}}</td>
              <td id="publish_year">{{book.publish_year}}</td>
              <td id="description">{{book.description}}</td>
              <td id="category">{{book.category}}</td>
              <td id="cover">
                <label :for="'cover'+book.id">Выберите изображение<img :src="book.cover" alt=""></label>
                <input disabled="true" :id="'cover'+book.id" type="file" class="form-control" v-on:change="onChange">
              </td>
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
              <td id="cover">
                <label for="cover_input_add" class="cover_grey">Выберите изображение</label>
                <input disabled="true" id="cover_input_add" type="file" class="form-control" v-on:change="onChange">
              </td>
              <td id="hidden"></td>
              <td>
                <button class="_btn addBtn" v-on:click="addElement($event, 'books')">
                  + Добавить
                </button>
              </td>
            </tr>
          </table>
        </div>     
        
        <div v-if="is_admin" class="manage">
          <h2>Управление разделами</h2>
            <table class=" _table">
              <tr>
                <th>Категория</th>
                <th>Описание</th>
                <th>Скрыто</th>
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
                <th>Скрыто</th>
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
              cover: '',
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
      onChange(e) {
                this.file = e.target.files[0];
            },
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
                  result.forEach((book, index, arr) => {                                   
                      this.tables['category'].forEach(author  => {
                        if (author['id'] == book['category']){
                            arr[index]['category'] = author['name'];
                        }
                      });               
                    });
                }

                if (!this.is_admin){  
                  this.tables[type] = []
                }                
                if (this.is_admin){                  
                  this.tables[type] = result;
                  return;
                } 
                
                const books = [];
                result.forEach(el => {  
                  if(el['added_by'] == this.user['id']){    
                    console.log(el)                  
                    books.push(el);
                    this.tables['books'] = books;                    
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

          row.querySelector("#cover input").disabled = state;

          if (state){            
            let elements = this.fields[type];

            for (let key in elements) { 
              if (key == 'cover'){
                if (row.querySelector("#cover input").files.length > 0){
                  elements[key] = row.querySelector("#cover input").files[0];
                  continue;
                }              
              }         
              if (key == 'category' || key == 'author'){
                elements[key] = row.querySelector("#"+key).children[0].value;
                continue;
              }
              elements[key] = row.querySelector("#"+key).innerText;
            }               
            
            if (elements['name'].replace(/\s/g, '').length == 0 && elements['name'].replace(/\s/g, '')  == ""){
              let elem = row.querySelector("#name");
              elem.classList.add("active")
              setTimeout(() => row.querySelector("#name").classList.remove("active"), 2000);
              return
            }
            event.target.innerText = "Редактировать";
            this.updateElement(table, elements, type);
          }




          let select_author = document.createElement('select');
          for (let key in this.tables['author']) {
            let option = document.createElement('option'); 
            option.value = this.tables['author'][key]['id'];
            option.innerText = this.tables['author'][key]['name'];
            select_author.append(option);
          }
          let select_cat = document.createElement('select');
          for (let key in this.tables['category']) {
            let option = document.createElement('option'); 
            option.value = this.tables['category'][key]['id'];
            option.innerText = this.tables['category'][key]['name'];
            select_cat.append(option);
          }

          for (let key in this.fields[type]) {
            let node = row.querySelector("#"+key);
            node.contentEditable = !state;
            if (key == "author" && !state){
              node.innerText = ""
              node.contentEditable = state;
              node.append(select_author)
              
            }
            if (key == "category" && !state){
              node.innerText = ""
              node.contentEditable = state;
              node.append(select_cat)
            }
            if (key == "cover" && !state){              
              node.contentEditable = state;
            }

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
          })
        },
        updateElement(e, elements, type){
          let data = new FormData();
          data.append('_method', 'PATCH');
          
          for (let key in elements) {
            data.append(key, elements[key]);
          }         
          const config = {
              headers: {
                  'content-type': 'multipart/form-data'
              }
          }
          axios.post(`/api/${type}/`+e.id, data, config).catch((error) => {

          })
        },
        addElement(event, type){
          this.add_check[type] = !this.add_check[type];  
          event.target.innerText = "Сохранить";        

          let row = event.target.parentElement.parentElement;
          
          row.querySelector("#cover input").disabled = !this.add_check[type];

          if (!this.add_check[type]){  
            let elements = this.fields[type];        
           
            for (let key in elements) {
              elements[key] = row.querySelector("#"+key).innerText;
              
              if (key == 'category' || key == 'author'){
                elements[key] = row.querySelector("#"+key).children[0].value;
              }
              if (key == 'cover'){
                if (row.querySelector("#cover input").files.length){
                  elements[key] = row.querySelector("#cover input").files[0];
                }              
              }

              row.querySelector("#"+key).innerText = '';            
            } 
            if (elements['name'].replace(/\s/g, '').length == 0 && elements['name'].replace(/\s/g, '')  == ""){
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

          let select_author = document.createElement('select');
          for (let key in this.tables['author']) {
            let option = document.createElement('option'); 
            option.value = this.tables['author'][key]['id'];
            option.innerText = this.tables['author'][key]['name'];
            select_author.append(option);
          }
          let select_cat = document.createElement('select');
          for (let key in this.tables['category']) {
            let option = document.createElement('option'); 
            option.value = this.tables['category'][key]['id'];
            option.innerText = this.tables['category'][key]['name'];
            select_cat.append(option);
          }

          for (let key in this.fields[type]) {
            console.log(key)
            let node = row.querySelector("#"+key);
            node.contentEditable = this.add_check[type];
            if (key == "author" && this.add_check[type]){
              node.innerText = ""
              node.contentEditable = !this.add_check[type];
              node.append(select_author)              
            }
            if (key == "category" && this.add_check[type]){
              node.innerText = ""
              node.contentEditable = !this.add_check[type];
              node.append(select_cat)
            }
            if (key == "cover" && this.add_check[type]){              
              node.contentEditable = !this.add_check[type];
            }
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

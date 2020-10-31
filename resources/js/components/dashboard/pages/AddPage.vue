<template>
  <v-main>
    <v-container fluid>
     <v-overlay :value="showFullLoading" :absolute="absolute">
				<v-progress-circular indeterminate size="64"></v-progress-circular>
			</v-overlay>
      <ToolbarLeft>
       <v-spacer></v-spacer>
      </ToolbarLeft>
      <Breadcrumbs />
      <v-row justify="center">
         <v-col sm="12" md="12" lg="12">
        	<v-card>
							<ImageModule :toggle="isImage" @send="receiveImage" @cancel="cancel"/>
								<v-card-title>
									<span class="headline">{{ formTitle }}</span>
								</v-card-title>

								<v-card-text>
									<v-container grid-list-md>
										<v-layout wrap>
											<v-col cols="6">
												<v-text-field
													:rules="[v => !!v || 'Title is required']"
													v-model="editedItem.title"
													label="Title*"
													filled
												></v-text-field>
											</v-col>
                                            <v-col cols="6">
                                                <v-select
                                                    v-model="editedItem.category_id"
                                                    :items="dataCategory"
                                                    item-text="name"
                                                    item-value="id"
                                                    :rules="[v => !!v || 'Category is required']"
                                                    label="Select Category"
                                                    required
                                                    filled
                                                ></v-select>
                                            	</v-col>
											<v-col cols="12">
                                           <ckeditor v-model="editedItem.body" :config="editorConfig"></ckeditor>
											</v-col>
											<v-col cols="12">
												<v-card
													class="mx-auto"
													width="180"
													outlined
													align="center"
													justify="center"
												>
												<v-img
												:src="editedItem.image?editedItem.image:'/images/plus.png'"
												aspect-ratio="1"
												@click="isImage=!isImage"
												>
												</v-img>
												<v-card-subtitle v-if="!editedItem.image">Add Banner</v-card-subtitle>
												<v-card-text v-else class="my-2">
													<v-btn x-small color="primary" @click="isImage=!isImage">
														Change
													</v-btn>
													<v-btn x-small color="primary" @click="editedItem.image=''">
														Remove
													</v-btn>
												</v-card-text>
												
												</v-card>
											</v-col>
                                            	
										</v-layout>
									</v-container>
								</v-card-text>

								<v-card-actions>
									<v-spacer></v-spacer>
								
									<v-btn
										color="primary"
										:loading="loading"
										:disabled="loading"
										text
										@click="save"
									>Save</v-btn>
								</v-card-actions>
							</v-card>
         </v-col>
             
      </v-row>
  
    </v-container>
    <v-snackbar
      v-model="snackbar"
      :vertical="snackvertical"
      :timeout="snacktimeout"
      :top="snacktop"
      :right="snackright"
      :color="snackcolor"
    >
      {{ snacktext }}
      <v-btn color="white" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </v-main>
</template>

<script>
import ImageModule from "./../../common/ImageModule";
import Breadcrumbs from "./../../common/Breadcrumbs";
import ToolbarLeft from "./../../common/ToolbarLeft";

export default {
  components: {
    ImageModule,
    ToolbarLeft,
    Breadcrumbs,
  },
  data: () => ({
    isImage: false,
    search: "",
    absolute: true,
  
    loading: false,
    edit: true,
  
    dataList: [],
    dataCategory:[],
    dataIndex: null,
		deleteTitle: "",
		deleteBody: "",
		isDelete: false,
        itemsPerPage:1,
        pageCount:2,
        editorConfig: {
                  toolbar: [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'ExportPdf', 'Preview', 'Print', '-', 'Templates' ] },
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
	{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
	{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
	{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
	'/',
	{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
	{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
	{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
	{ name: 'others', items: [ '-' ] },
	{ name: 'about', items: [ 'About' ] }
	]
                },
  
    editedIndex: -1,
    editedItem: {
      title: "",
      image: "",
      body:'',
      status: 1,
      category_id:"",
    },
    dataIndex: null,
    deleteTitle: "",
    deleteBody: "",
    isDelete: false,
    defaultItem: {
    title: "",
      image: "",
      body:'',
      status: 1,
      category_id:"",
    },
  
    filters:
        {
			page: 1,
			show: 20,
        },
  }),

  props: 
  {
    source: String,
  },

  computed: 
  {
    formTitle() 
    {
      return this.editedIndex === -1 ? "New Page" : "Edit Page";
    },
  },

  watch: {},

  created() 
  {
    this.initialize();
  },

  methods: 
  {
      
    cancel() 
    {
      this.isImage = !this.isImage;
    },

    receiveImage(item) 
    {
      this.isImage = !this.isImage;
      this.editedItem.image = item.src;
    },

    async initialize() 
    {
        try 
			 {
        		let { data } = await axios({
          		method: "get",
          		url: "/app/category"
        		});
        		this.dataCategory = data;
			} 
			catch (e) 
			{

            }
            
            if( this.$route.params.id)
            {
                try 
                {
                let { data } = await axios({
                    method: "get",
                    url: "/app/pages/" + this.$route.params.id,
                });
                	this.editedItem = data;
                } 
                catch (e) 
                {
                this.snacks("Failed", "red");
                this.loading = false;
                }
            }
     
    },
    async save() 
    {
      this.loading = true;
      if (this.$route.params.id) 
      {
        try 
        {
          let { data } = await axios({
            method: "put",
            url: "/app/pages/" + this.$route.params.id,
            data: this.editedItem,
          });
          if (data.status) 
          {
            this.snacks("Successfully Added", "green");
            this.$router.push('/pages/pageslist');
            this.close();
          } 
          else 
          {
            this.snacks("Failed", "red");
            this.loading = false;
          }
        } 
        catch (e) 
        {
          this.snacks("Failed", "red");
          this.loading = false;
        }
      } 
      else 
      {
        try 
        {
          
          let { data } = await axios({
            method: "post",
            url: "/app/pages",
            data: this.editedItem,
          });
          console.log(data);
          if (data.status) 
          {
           
            this.snacks("Successfully Added", "green");
            this.$router.push('/pages/pageslist');
          } 
          else 
          {
            this.snacks("Failed", "red");
            this.loading = false;
          }
        } 
        catch (e) 
        {
          this.snacks("Failed", "red");
          this.loading = false;
        }
      }
    },
    async remove() {
      this.loading = true;
      try {
        let { data } = await axios({
          method: "delete",
          url: "/app/pages/" + this.dataList[this.dataIndex].id,
        });
        if (data.status) {
          this.snacks("Successfully Done", "green");
          this.dataList.splice(this.dataIndex, 1);
          this.close();
        } else {
          this.snacks(data.data, "red");
          this.loading = false;
        }
      } catch (e) {
        this.snacks("Operation Failed", "red");
        this.loading = false;
      }
    },
  },
};
</script>

<template>
    <div>
      <ul>
        <li v-for="message in messages" :key="message.id">
          <div :class="message.sender">{{ message.content }}</div>
        </li>
      </ul>
      <input v-model="newMessage" placeholder="Escribe un mensaje" />
      <button @click="sendMessage">Enviar</button>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        messages: [], // Lista de mensajes
        newMessage: '', // Mensaje nuevo
      };
    },
    mounted() {
      this.loadMessages(); // Cargar mensajes cuando se monta el componente
      this.messageInterval = setInterval(this.loadMessages, 5000); // Cargar mensajes cada 5 segundos
    },
    beforeDestroy() {
      clearInterval(this.messageInterval); // Limpiar intervalo al destruir el componente
    },
    methods: {
      async loadMessages() {
        try {
          const response = await axios.get('/chat/recent-messages');
          this.messages = response.data;
        } catch (error) {
          console.error('Error al cargar los mensajes:', error);
        }
      },
      async sendMessage() {
        try {
          await axios.post('/chat/send', {
            content: this.newMessage,
            sender: 'user',
          });
          this.newMessage = ''; // Limpiar el campo de entrada
          this.loadMessages(); // Recargar mensajes después de enviar uno nuevo
        } catch (error) {
          console.error('Error al enviar el mensaje:', error);
        }
      },
    },
  };
  </script>
  
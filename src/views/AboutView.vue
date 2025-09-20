<template>
  <div class="gif-container">
    <img :src="gifFile" alt="vue gif" width="500" height="500" />
  </div>
  <div class="about-text">YOOHOO!!!
    <div class="spinner-border text-danger" role="status">
  <span class="visually-hidden">Loading...</span>
</div>
  </div>
  
</template>

<script>
import gifFile from "@/assets/fuzzy.gif"; // or "/images/animation.gif" if in public/

export default {
  name: "GifExample",
  data() {
    return {
      gifFile,
    };
  },
};
</script>

<style scoped>
.gif-container {
  display: flex;
  justify-content: center; /* horizontal center */
  align-items: center;     /* vertical center */
  height: 50vh;           /* full screen height */           /* full screen width */
}

.about-text {
  text-align: center;
  font-size: 54px;
  margin-top: 20px;
  font-weight: bold;
}
.spinner-border {
  width: 3rem;
  height: 3rem;
  margin-left: 10px;
}
</style>

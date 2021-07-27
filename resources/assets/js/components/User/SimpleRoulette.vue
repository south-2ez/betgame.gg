<template>
  <div>
    <section class="section">

      <div class="columns">
        <div class="column">

          <div class="columns is-centered" v-if="!spinned" @click="spinned = !spinned">
            <input type="button" class="btn btn-sm btn-default" value="SPIN" id='spin' v-on:click="spin" :disabled="spinned" v-show="showSpinButton"/>  
          </div>
          <!-- <div class="columns is-centered">
            <input type="button" class="btn btn-default" value="SPIN" id='spin' v-on:click="spin" :disabled="spinned"/>  
          </div> -->
    
          <div class="columns is-centered">
            <canvas id="canvas" :width="canvasWidth" :height="canvasHeight"></canvas>
          </div>
        </div>
      </div>
    
  </section>
  </div>
</template>
<script>
import embossLogo from '../../../images/logo-emboss.png'
export default {
  props: {
    options: {
      required: true,
      type: Array
    },
    canvasWidth: {
      default: 500
    },
    canvasHeight: {
      default: 500
    },
    spinAngleStart : {
      default: Math.random() * 10 + 10
    },
    spinTimeTotal: {
      default:  Math.random() * 3 + 4 * 3000,
    }

  },
  data() {
    return {
      new_option: '',
      startAngle: 0,
      startAngleStart: 0,
      spinTimeout: null,
      spinArcStart: 10,
      spinTime: 0,
      ctx: '',
      spinned: false,
      embossLogo : embossLogo,
      showSpinButton: false,
    }
  },

  computed:{
    arc: function () {
      return Math.PI / (this.options.length / 2);
    } 
  },

  methods: {
    byte2Hex: function (n) {
      var nybHexString = "0123456789ABCDEF";
      return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
    },

    RGB2Color: function (r,g,b) {
      return '#' + this.byte2Hex(r) + this.byte2Hex(g) + this.byte2Hex(b);
    },

    getColor: function (item, maxitem) {
      var phase = 0;
      var center = 128;
      var width = 127;
      var frequency = Math.PI*2/maxitem;
      
      const red   = Math.sin(frequency*item+2+phase) * width + center;
      const green = Math.sin(frequency*item+0+phase) * width + center;
      const blue  = Math.sin(frequency*item+4+phase) * width + center;
      
      return this.RGB2Color(red,green,blue);
    },

    addOptions: function () {
      this.options.push(this.new_option);
      this.new_option = '';
      this.drawRouletteWheel()
    },
    removeOptions: function (option) {
      let idx = this.options.indexOf(option) || 0;
      this.options.splice(idx, 1);
      this.drawRouletteWheel();

    },   

    drawRouletteWheel: function () {
      var canvas = document.getElementById("canvas");
  
      if (canvas.getContext) {

        //halloween
        // var outsideRadius = 180;
        // var textRadius = 140;
        // var insideRadius = 80;

        //xmas
        var outsideRadius = 180;
        var textRadius = 140;
        var insideRadius = 0;

        this.ctx = canvas.getContext("2d");
        this.ctx.clearRect(0,0,this.canvasWidth,this.canvasHeight);

        this.ctx.strokeStyle = "#ceaa13";
        this.ctx.lineWidth = 10;

        this.ctx.font = 'bold 12px Helvetica, Arial';

        let insertImage = document.createElement('img');
        insertImage.src = this.embossLogo;
        insertImage.width = '75px';
        insertImage.height = '75px';

        const that = this;
          insertImage.onload = function() {
          

          for(var i = 0; i < that.options.length; i++) {
            var angle = that.startAngle + i * that.arc;
            var text = that.options[i];
            //that.ctx.fillStyle = colors[i];
            if(i % 2 == 0){
              that.ctx.fillStyle = '#ff0808';
            }else{
              that.ctx.fillStyle = '#ffffff';
            }
            
            console.log('i : ',i, text, 'color : ,', that.ctx.fillStyle)
            that.ctx.beginPath();

            that.ctx.arc(250, 250, outsideRadius, angle, angle + that.arc, false);
            that.ctx.arc(250, 250, insideRadius, angle + that.arc, angle, false);
        
            that.ctx.stroke();
            that.ctx.fill();

            that.ctx.save();
            that.ctx.shadowOffsetX = -1;
            that.ctx.shadowOffsetY = -1;
            that.ctx.shadowBlur    = 0;
            // that.ctx.shadowColor   = "rgb(220,220,220)";
            
            // that.ctx.shadowColor   = "#ceaa13";

            if(i % 2 == 0){
              that.ctx.fillStyle = '#ffffff';
            }else{
              that.ctx.fillStyle = '#ff0808';
            }

            that.ctx.translate(250 + Math.cos(angle + that.arc / 2) * textRadius, 
                          250 + Math.sin(angle + that.arc / 2) * textRadius);
            that.ctx.rotate(angle + that.arc / 2 + Math.PI / 2);
            
            that.ctx.fillText(text, -that.ctx.measureText(text).width / 2, 0);
            that.ctx.restore();
          } 

          that.ctx.drawImage( insertImage, (that.canvasWidth /2 ) - 37.5,  (that.canvasHeight /2 ) - 37.5 , 75, 75);

          //Arrow
          that.ctx.fillStyle = "black";
          that.ctx.beginPath();
          that.ctx.moveTo(250 - 4, 250 - (outsideRadius + 5));
          that.ctx.lineTo(250 + 4, 250 - (outsideRadius + 5));
          that.ctx.lineTo(250 + 4, 250 - (outsideRadius - 5));
          that.ctx.lineTo(250 + 9, 250 - (outsideRadius - 5));
          that.ctx.lineTo(250 + 0, 250 - (outsideRadius - 13));
          that.ctx.lineTo(250 - 9, 250 - (outsideRadius - 5));
          that.ctx.lineTo(250 - 4, 250 - (outsideRadius - 5));
          that.ctx.lineTo(250 - 4, 250 - (outsideRadius + 5));
          that.ctx.fill();          
        };

      
        // for(var i = 0; i < this.options.length; i++) {
        //   var angle = this.startAngle + i * this.arc;
        //   var text = this.options[i];
        //   //this.ctx.fillStyle = colors[i];
        //   if(i % 2 == 0){
        //     this.ctx.fillStyle = '#ff0808';
        //   }else{
        //     this.ctx.fillStyle = '#ffffff';
        //   }
          
        //   console.log('i : ',i, text, 'color : ,', this.ctx.fillStyle)
        //   this.ctx.beginPath();

        //   this.ctx.arc(250, 250, outsideRadius, angle, angle + this.arc, false);
        //   this.ctx.arc(250, 250, insideRadius, angle + this.arc, angle, false);
      
        //   this.ctx.stroke();
        //   this.ctx.fill();

        //   this.ctx.save();
        //   this.ctx.shadowOffsetX = -1;
        //   this.ctx.shadowOffsetY = -1;
        //   this.ctx.shadowBlur    = 0;
        //   // this.ctx.shadowColor   = "rgb(220,220,220)";
          
        //   // this.ctx.shadowColor   = "#ceaa13";

        //   if(i % 2 == 0){
        //     this.ctx.fillStyle = '#ffffff';
        //   }else{
        //     this.ctx.fillStyle = '#ff0808';
        //   }

        //   this.ctx.translate(250 + Math.cos(angle + this.arc / 2) * textRadius, 
        //                 250 + Math.sin(angle + this.arc / 2) * textRadius);
        //   this.ctx.rotate(angle + this.arc / 2 + Math.PI / 2);
          
        //   this.ctx.fillText(text, -this.ctx.measureText(text).width / 2, 0);
        //   this.ctx.restore();
        // } 


      //adding image
      // // Clip by the stroke.
      //   ctx.clip();
      //   // Draw image.
        
      //   // 
      //   ctx.drawImage(image, 0, 0, iw, ih, 95 - tw/2, 50 - th/2, tw, th);
        
      //   // Restore the context, otherwise the text will also be clipped.
      //   ctx.restore();
  


        // //Arrow
        // this.ctx.fillStyle = "black";
        // this.ctx.beginPath();
        // this.ctx.moveTo(250 - 4, 250 - (outsideRadius + 5));
        // this.ctx.lineTo(250 + 4, 250 - (outsideRadius + 5));
        // this.ctx.lineTo(250 + 4, 250 - (outsideRadius - 5));
        // this.ctx.lineTo(250 + 9, 250 - (outsideRadius - 5));
        // this.ctx.lineTo(250 + 0, 250 - (outsideRadius - 13));
        // this.ctx.lineTo(250 - 9, 250 - (outsideRadius - 5));
        // this.ctx.lineTo(250 - 4, 250 - (outsideRadius - 5));
        // this.ctx.lineTo(250 - 4, 250 - (outsideRadius + 5));
        // this.ctx.fill();

      }

      this.showSpinButton = true;
    },

    spin: function () {
      this.spinTime = 0;
      this.rotateWheel();
    },

    rotateWheel: function () {
      this.spinTime += 30;
      if(this.spinTime >= this.spinTimeTotal) {
        this.stopRotateWheel();
        return;
      }
      var spinAngle = this.spinAngleStart - this.easeOut(this.spinTime, 0, this.spinAngleStart, this.spinTimeTotal);

      this.startAngle += (spinAngle * Math.PI / 180);
      this.drawRouletteWheel();

      let _this = this
      this.spinTimeout = setTimeout(function() {
        _this.rotateWheel()
      }, 30);
    },

    stopRotateWheel: function () {
      clearTimeout(this.spinTimeout);
      var degrees = this.startAngle * 180 / Math.PI + 90;
      var arcd = this.arc * 180 / Math.PI;
      var index = Math.floor((360 - degrees % 360) / arcd);
      this.ctx.save();
      var text = !!this.options[index] ? `You won ${this.options[index]} credits!` : "Better luck next time."
      
      this.ctx.font = 'bold 30px Helvetica, Arial';
      this.ctx.fillText(text, 250 - this.ctx.measureText(text).width / 2, 250 + 10);
      this.ctx.restore();

      const doneData = {
        text,
        index,
        options: this.options,
      }

      this.$emit('doneSpin',doneData);
    },

    easeOut: function (t, b, c, d) {
      var ts = (t/=d)*t;
      var tc = ts*t;
      return b+c*(tc + -3*ts + 3*t);
    }
  },

  mounted() {
    this.drawRouletteWheel();
  }  
}
</script>
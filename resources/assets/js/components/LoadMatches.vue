<template>
  <div
    v-infinite-scroll="loadMore"
    :infinite-scroll-disabled="busy || offset > maxTotal"
    infinite-scroll-throttle-delay="1000"
  >
    <div v-for="match in matches" :key="`match-${match.id}`" class="matchmain">
      <div class="infor">
        <div class="time">
          <span
            v-if="match.status == 'ongoing'"
            style="color: #72A326; text-shadow: 1px 1px 0px #4A7010; font-weight: bold; font-size: 16px"
            >&nbsp;LIVE</span
          >
          <strong
            v-if="match.status == 'open'"
            class="match_countdown"
            :data-schedule="match.schedule"
            :id="`match-countdown-timer-${match.id}`"
          >
            {{ countdownSchedule(match.schedule) }}
          </strong>
        </div>
        <div class="series">{{ match.league.name }}</div>
      </div>

      <div class="match " :style="`background-image: url('/public_image/${match.league.image}');`">
        <div class="col-sm-10" style="text-align: center; font-weight: bold;">
          <span class="matchLabel">{{ match.label }}</span>
        </div>
        <div class="col-sm-10 matchleft">
          <a :href="`/match/${match.id}`"
            ><div style="width: 45%; float: left; text-align: right;">
              <img :src="`/${match.team_a.image}`" class="team2_img" style="float: right; border-radius: 2px;" />
              <div class="teamtext">
                <b class="txtteam" style="font-size: 0.9vw;">{{ match.team_a.name }} {{match.ratio}}</b
                ><br />
                <i class="percent-coins">{{ match.ratio.team_a_percentage }}%</i>
              </div>
            </div>
            <div class="vs_div" style="float: left; text-align: center; margin-top: 0.6em;">
              <span class="format" style="background: rgb(255, 255, 255);">{{ match.best_of }}</span
              ><br /><span style="background: rgb(255, 255, 255);">vs</span>
            </div>
            <div style="width: 45%; float: left; text-align: left;">
              <img :src="`/${match.team_b.image}`" class="team2_img" style="float: left; border-radius: 2px;" />
              <div class="teamtext">
                <b class="txtteam" style="font-size: 0.8vw;">{{ match.team_b.name }}</b
                ><br />
                <i class="percent-coins">{{ match.ratio.team_b_percentage }}%</i>
              </div>
            </div></a
          >
        </div>
      </div>
    </div>

    <div class="text-center" v-if="busy">
      <img src="/images/loading.gif" style="width: 50px;" /> <br />
      Loading more matches...
    </div>
  </div>
</template>

<script>
  export default {
    mame: 'LoadMatches',
    props: ['matchType', 'maxTotal'],
    data: function() {
      return {
        matches: [],
        busy: false,
        url: '/matches/open-live',
        offset: 10,
        take: 5,
        matchTime: {},
        now: new Date().getTime(),
      };
    },
    methods: {
      loadMore() {
        // console.log('load more...');
        if (this.offset < this.maxTotal && !this.busy) {
          this.busy = true;
          $.get(this.getMatchUrl).then(response => {
            const { matches, offset } = response;

            this.matches = [...this.matches, ...matches];
            this.offset = offset;
            this.busy = false;
          });
        }
      },

      countdownSchedule(time) {

        const countDownDate = parseInt(moment(time).format('x'));
        // Find the distance between now an the count down date
        const distance = countDownDate - this.now;

        // Time calculations for days, hours, minutes and seconds
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        return distance < 0
          ? 'Match will start soon'
          : (days > 0 ? days + 'd ' : '') +
              (hours > 0 ? hours + 'h ' : '') +
              (minutes > 0 ? minutes + 'm ' : '') +
              seconds +
              's from now';
        // If the count down is finished, write some text
        // if (distance < 0) {
        //   countDownText = 'Match will start soon';
        //   //element.innerHTML = 'Match will start soon';
        // } else {
        //   countDownText =
        //     (days > 0 ? days + 'd ' : '') +
        //     (hours > 0 ? hours + 'h ' : '') +
        //     (minutes > 0 ? minutes + 'm ' : '') +
        //     seconds +
        //     's from now';
        //   return countDownText;
        // }
      }
    },
    computed: {
      getMatchUrl: function() {
        const matchType = !!this.matchType ? this.matchType : 'all';

        return `${this.url}/${matchType}/${this.offset}/${this.take}`;
      }
    },
    mounted() {
      // console.log('load matches component mounted.');
      window.setInterval(() => {
        this.now = new Date().getTime();
      }, 1000);
    }
  };
</script>

<style></style>

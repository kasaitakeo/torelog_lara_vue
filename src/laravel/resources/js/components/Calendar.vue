<template>
  <div class="content">
    <h2>{{ displayMonth }}</h2>
    <div class="button-area">
      <button @click="prevMonth" class="button">前の月</button>
      <button @click="nextMonth" class="button">次の月</button>
    </div>
    <div class="calendar">
      <div class="calendar-weekly">
        <div class="calendar-youbi" v-for="n in 7" :key="n">
          {{ youbi(n-1) }}
        </div>
      </div>
      <div 
        class="calendar-weekly"
        v-for="(week, index) in calendars"
        :key="index"
      >
        <div
          class="calendar-daily"
          v-for="(day, index) in week"
          :key="index"
        >
          <div class="calendar-day">
            {{ day.day }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
export default {
  data () {
    return {
      currentDate: moment(),
    };
  },
  methods: {
    // 最初の日曜日
    getStartDate () {
      let date = moment(this.currentDate);
      // startOfメソッドの引数に”month”を入れるとその月の最初の日
      date.startOf("month");
      // dayメソッドで曜日を出す(日曜日の0が基準
      const youbiNum = date.day();
      // momentのsubstractメソッドを利用し第一引数に先ほど出した曜日の数字、第二引数は日付の引き算を行うのでdaysを設定
      return date.subtract(youbiNum, "days");
    },
    // 最後の土曜日
    getEndDate () {
      let date = moment(this.currentDate);
      // その月の最後の日
      date.endOf("month");
      const youbiNum = date.day();
      // その月の最終日からその週の土曜日の日付を出すために6から曜日の数字を引いた数を足す
      return date.add(6 - youbiNum, "days");
    },
    getCalendar () {
      let startDate = this.getStartDate();
      const endDate = this.getEndDate();
      // カレンダーの最初の日と最後の日の差を取り7で割ることで高さを決める
      // momentのdiffメソッドを使って差の日数を出し、出した数字をMath.ceilで切り上げる
      const weekNumber = Math.ceil(endDate.diff(startDate, "days") / 7);

      let calendars = [];
      // weekNumber(週の数)でループを行う
      for (let week = 0; week < weekNumber; week++) {
        let weekRow = [];
        // 1週間分(7)ループを行う
        for (let day = 0;  day < 7; day++) {
          weekRow.push({
            day: startDate.get("date"),
          });
          // ループのたびに1日追加
          startDate.add(1, "days");
        }
        calendars.push(weekRow);
      }
      return calendars;
    },
    nextMonth () {
      this.currentDate = moment(this.currentDate).add(1, "month");
    },
    prevMonth () {
      this.currentDate = moment(this.currentDate).subtract(1, "month");
    },
    // 曜日の数字から日本語の曜日に変更を行うyoubiメソッドを追加
    youbi (dayIndex) {
      const week = ["日", "月", "火", "水", "木", "金", "土"];
      return week[dayIndex];
    },
  },
  computed: {
    calendars () {
      return this.getCalendar();
    },
    displayMonth () {
    return this.currentDate.format('YYYY[年]M[月]')
  },
  },
}
</script>

<style>
.content{
  margin:2em auto;
  width:900px;
}
.button-area{
  margin:0.5em 0;
}
.button{
  padding:4px 8px;
  margin-right:8px;
}
.calendar{
  max-width:900px;
  border-top:1px solid #E0E0E0;
  font-size:0.8em;
}
.calendar-weekly{
  display:flex;
  border-left:1px solid #E0E0E0;
  /* background-color: black; */
}
.calendar-youbi{
  flex:1;
  border-right:1px solid #E0E0E0;
  margin-right:-1px;
  text-align:center;
}
.calendar-daily{
  flex:1;
  min-height:125px;
  border-right:1px solid #E0E0E0;
  border-bottom:1px solid #E0E0E0;
  margin-right:-1px;
}
.calendar-day{
  text-align: center;
}
</style>
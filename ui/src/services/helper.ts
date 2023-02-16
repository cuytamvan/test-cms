import { DateFormat, FormatData, ListDate } from '@/instances/ui.instance';
import { range, chunk } from 'lodash';

const helper = {
  randId(len: number): string {
    let res = '';
    const char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const charLen = char.length;

    for (let i = 0; i < len; i++) {
      res += char.charAt(Math.floor(Math.random() * charLen));
    }

    return res;
  },

  beautyDate(str: string): string {
    const date = new Date(str);

    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'long' });
    const year = date.getFullYear();

    return `${day} ${month} ${year}`;
  },

  dateFormat(date: Date): DateFormat {
    const y = date.getFullYear();
    const m = date.getMonth();
    const d = date.getDate();

    const day = date.getDay();

    return { y, m, d, day };
  },

  now() {
    return this.dateFormat(new Date());
  },

  lastDate(year: number, month: number): number {
    const date = new Date(year, month, 0);
    const formated = this.dateFormat(date);

    return formated.d;
  },

  getListDate(year: number, month: number): ListDate[] {
    const now = this.now();
    const lastDayOfLastMonth = this.lastDate(year, month) + 1;
    const lastDayOfThisMonthArr = this.range(1, this.lastDate(year, month + 1) + 1);

    let lastMonth: ListDate[] = [];
    let thisMonth: ListDate[] = [];
    let nextMonth: ListDate[] = [];

    const replaceDay = (day: number): number => {
      const newDay = day + 1;
      return newDay;
    };

    const formater = (date: DateFormat) => {
      const m = date.m + 1;
      const d = date.d;

      return `${date.y}-${m <= 9 ? `0${m}` : m}-${d <= 9 ? `0${d}` : d}`;
    };

    thisMonth = lastDayOfThisMonthArr.map((day) => {
      const date = this.dateFormat(new Date(year, month, day));

      return {
        day,
        dayOfWeek: replaceDay(date.day),
        today: year === now.y && month === now.m && now.d === day,
        date: formater(date),
        thisMonth: true,
      };
    });

    const firstDate = thisMonth[0].dayOfWeek;
    const lastDate = thisMonth[thisMonth.length - 1].dayOfWeek;

    if (firstDate !== 0) {
      lastMonth = this.range(lastDayOfLastMonth - firstDate + 1, lastDayOfLastMonth).map((day) => {
        const date = this.dateFormat(new Date(year, month - 1, day));

        return {
          day,
          dayOfWeek: replaceDay(date.day),
          today: false,
          date: formater(date),
          thisMonth: false,
        };
      });
    }

    if (lastDate !== 7) {
      nextMonth = this.range(1, 8 - lastDate).map((day) => {
        const date = this.dateFormat(new Date(year, month + 1, day));

        return {
          day,
          dayOfWeek: replaceDay(date.day),
          today: false,
          date: formater(date),
          thisMonth: false,
        };
      });
    }

    return [...lastMonth, ...thisMonth, ...nextMonth];
  },

  getFormatForm(input: any, except: string[] = [], name = ''): FormatData[] {
    let result: FormatData[] = [];
    for (const key in input) {
      const newKey = name.length ? `${name}[${key}]` : key;
      if ((Array.isArray(input[key]) || typeof input[key] === 'object') && !except.filter((r) => r === key).length) {
        result = [...result, ...this.getFormatForm(input[key], except, newKey)];
      } else {
        result = [
          ...result,
          {
            name: newKey,
            value: input[key],
          },
        ];
      }
    }
    return result;
  },

  setformdata(input: any, except: string[] = []) {
    const form = new FormData();

    this.getFormatForm(input, except).map((r) => {
      form.append(r.name, r.value);
    });

    return form;
  },

  range,
  chunk,
};

export default helper;

<?php
 namespace backend\services;

 class CityInfoService{


     public static function cityInfolist(){
         $data = json_decode(static::cityInfo());
         $result = [];
         if(!empty($data)){
             foreach($data as $key =>$value){
                    $result[$value->value] = $value->name;
             }
         }
         return ($result);
     }

     public static function cityInfo(){
         return
             '[{
            "value": "1",
            "parent": "0",
            "name": "中国"
        },
        {
            "value": "11",
            "parent": "1",
            "name": "北京"
        },
        {
            "value": "12",
            "parent": "1",
            "name": "天津"
        },
        {
            "value": "13",
            "parent": "1",
            "name": "河北"
        },
        {
            "value": "14",
            "parent": "1",
            "name": "山西"
        },
        {
            "value": "15",
            "parent": "1",
            "name": "内蒙古"
        },
        {
            "value": "21",
            "parent": "1",
            "name": "辽宁"
        },
        {
            "value": "22",
            "parent": "1",
            "name": "吉林"
        },
        {
            "value": "23",
            "parent": "1",
            "name": "黑龙江"
        },
        {
            "value": "31",
            "parent": "1",
            "name": "上海"
        },
        {
            "value": "32",
            "parent": "1",
            "name": "江苏"
        },
        {
            "value": "33",
            "parent": "1",
            "name": "浙江"
        },
        {
            "value": "34",
            "parent": "1",
            "name": "安徽"
        },
        {
            "value": "35",
            "parent": "1",
            "name": "福建"
        },
        {
            "value": "36",
            "parent": "1",
            "name": "江西"
        },
        {
            "value": "37",
            "parent": "1",
            "name": "山东"
        },
        {
            "value": "41",
            "parent": "1",
            "name": "河南"
        },
        {
            "value": "42",
            "parent": "1",
            "name": "湖北"
        },
        {
            "value": "43",
            "parent": "1",
            "name": "湖南"
        },
        {
            "value": "44",
            "parent": "1",
            "name": "广东"
        },
        {
            "value": "45",
            "parent": "1",
            "name": "广西"
        },
        {
            "value": "46",
            "parent": "1",
            "name": "海南"
        },
        {
            "value": "50",
            "parent": "1",
            "name": "重庆"
        },
        {
            "value": "51",
            "parent": "1",
            "name": "四川"
        },
        {
            "value": "52",
            "parent": "1",
            "name": "贵州"
        },
        {
            "value": "53",
            "parent": "1",
            "name": "云南"
        },
        {
            "value": "54",
            "parent": "1",
            "name": "西藏"
        },
        {
            "value": "61",
            "parent": "1",
            "name": "陕西"
        },
        {
            "value": "62",
            "parent": "1",
            "name": "甘肃"
        },
        {
            "value": "63",
            "parent": "1",
            "name": "青海"
        },
        {
            "value": "64",
            "parent": "1",
            "name": "宁夏回族自治区"
        },
        {
            "value": "65",
            "parent": "1",
            "name": "新疆维吾尔自治区"
        },
        {
            "value": "71",
            "parent": "1",
            "name": "台湾"
        },
        {
            "value": "81",
            "parent": "1",
            "name": "香港"
        },
        {
            "value": "91",
            "parent": "1",
            "name": "澳门"
        },
        {
            "value": "1301",
            "parent": "13",
            "name": "石家庄"
        },
        {
            "value": "1302",
            "parent": "13",
            "name": "唐山"
        },
        {
            "value": "1303",
            "parent": "13",
            "name": "秦皇岛"
        },
        {
            "value": "1304",
            "parent": "13",
            "name": "邯郸"
        },
        {
            "value": "1305",
            "parent": "13",
            "name": "邢台"
        },
        {
            "value": "1306",
            "parent": "13",
            "name": "保定"
        },
        {
            "value": "1307",
            "parent": "13",
            "name": "张家口"
        },
        {
            "value": "1308",
            "parent": "13",
            "name": "承德"
        },
        {
            "value": "1309",
            "parent": "13",
            "name": "沧州"
        },
        {
            "value": "1310",
            "parent": "13",
            "name": "廊坊"
        },
        {
            "value": "1311",
            "parent": "13",
            "name": "衡水"
        },
        {
            "value": "1401",
            "parent": "14",
            "name": "太原"
        },
        {
            "value": "1402",
            "parent": "14",
            "name": "大同"
        },
        {
            "value": "1403",
            "parent": "14",
            "name": "阳泉"
        },
        {
            "value": "1404",
            "parent": "14",
            "name": "长治"
        },
        {
            "value": "1405",
            "parent": "14",
            "name": "晋城"
        },
        {
            "value": "1406",
            "parent": "14",
            "name": "朔州"
        },
        {
            "value": "1407",
            "parent": "14",
            "name": "晋中"
        },
        {
            "value": "1408",
            "parent": "14",
            "name": "运城"
        },
        {
            "value": "1409",
            "parent": "14",
            "name": "忻州"
        },
        {
            "value": "1410",
            "parent": "14",
            "name": "临汾"
        },
        {
            "value": "1423",
            "parent": "14",
            "name": "吕梁地区"
        },
        {
            "value": "1501",
            "parent": "15",
            "name": "呼和浩特"
        },
        {
            "value": "1502",
            "parent": "15",
            "name": "包头"
        },
        {
            "value": "1503",
            "parent": "15",
            "name": "乌海"
        },
        {
            "value": "1504",
            "parent": "15",
            "name": "赤峰"
        },
        {
            "value": "1505",
            "parent": "15",
            "name": "通辽"
        },
        {
            "value": "1521",
            "parent": "15",
            "name": "呼伦贝尔盟"
        },
        {
            "value": "1522",
            "parent": "15",
            "name": "兴安盟"
        },
        {
            "value": "1525",
            "parent": "15",
            "name": "锡林郭勒盟"
        },
        {
            "value": "1526",
            "parent": "15",
            "name": "乌兰察布盟"
        },
        {
            "value": "1527",
            "parent": "15",
            "name": "伊克昭盟"
        },
        {
            "value": "1528",
            "parent": "15",
            "name": "巴彦淖尔盟"
        },
        {
            "value": "1529",
            "parent": "15",
            "name": "阿拉善盟"
        },
        {
            "value": "2101",
            "parent": "21",
            "name": "沈阳"
        },
        {
            "value": "2102",
            "parent": "21",
            "name": "大连"
        },
        {
            "value": "2103",
            "parent": "21",
            "name": "鞍山"
        },
        {
            "value": "2104",
            "parent": "21",
            "name": "抚顺"
        },
        {
            "value": "2105",
            "parent": "21",
            "name": "本溪"
        },
        {
            "value": "2106",
            "parent": "21",
            "name": "丹东"
        },
        {
            "value": "2107",
            "parent": "21",
            "name": "锦州"
        },
        {
            "value": "2108",
            "parent": "21",
            "name": "营口"
        },
        {
            "value": "2109",
            "parent": "21",
            "name": "阜新"
        },
        {
            "value": "2110",
            "parent": "21",
            "name": "辽阳"
        },
        {
            "value": "2111",
            "parent": "21",
            "name": "盘锦"
        },
        {
            "value": "2112",
            "parent": "21",
            "name": "铁岭"
        },
        {
            "value": "2113",
            "parent": "21",
            "name": "朝阳"
        },
        {
            "value": "2114",
            "parent": "21",
            "name": "葫芦岛"
        },
        {
            "value": "2201",
            "parent": "22",
            "name": "长春"
        },
        {
            "value": "2202",
            "parent": "22",
            "name": "吉林"
        },
        {
            "value": "2203",
            "parent": "22",
            "name": "四平"
        },
        {
            "value": "2204",
            "parent": "22",
            "name": "辽源"
        },
        {
            "value": "2205",
            "parent": "22",
            "name": "通化"
        },
        {
            "value": "2206",
            "parent": "22",
            "name": "白山"
        },
        {
            "value": "2207",
            "parent": "22",
            "name": "松原"
        },
        {
            "value": "2208",
            "parent": "22",
            "name": "白城"
        },
        {
            "value": "2224",
            "parent": "22",
            "name": "延边朝鲜族自治州"
        },
        {
            "value": "2301",
            "parent": "23",
            "name": "哈尔滨"
        },
        {
            "value": "2302",
            "parent": "23",
            "name": "齐齐哈尔"
        },
        {
            "value": "2303",
            "parent": "23",
            "name": "鸡西"
        },
        {
            "value": "2304",
            "parent": "23",
            "name": "鹤岗"
        },
        {
            "value": "2305",
            "parent": "23",
            "name": "双鸭山"
        },
        {
            "value": "2306",
            "parent": "23",
            "name": "大庆"
        },
        {
            "value": "2307",
            "parent": "23",
            "name": "伊春"
        },
        {
            "value": "2308",
            "parent": "23",
            "name": "佳木斯"
        },
        {
            "value": "2309",
            "parent": "23",
            "name": "七台河"
        },
        {
            "value": "2310",
            "parent": "23",
            "name": "牡丹江"
        },
        {
            "value": "2311",
            "parent": "23",
            "name": "黑河"
        },
        {
            "value": "2312",
            "parent": "23",
            "name": "绥化"
        },
        {
            "value": "2327",
            "parent": "23",
            "name": "大兴安岭地区"
        },
        {
            "value": "3201",
            "parent": "32",
            "name": "南京"
        },
        {
            "value": "3202",
            "parent": "32",
            "name": "无锡"
        },
        {
            "value": "3203",
            "parent": "32",
            "name": "徐州"
        },
        {
            "value": "3204",
            "parent": "32",
            "name": "常州"
        },
        {
            "value": "3205",
            "parent": "32",
            "name": "苏州"
        },
        {
            "value": "3206",
            "parent": "32",
            "name": "南通"
        },
        {
            "value": "3207",
            "parent": "32",
            "name": "连云港"
        },
        {
            "value": "3208",
            "parent": "32",
            "name": "淮安"
        },
        {
            "value": "3209",
            "parent": "32",
            "name": "盐城"
        },
        {
            "value": "3210",
            "parent": "32",
            "name": "扬州"
        },
        {
            "value": "3211",
            "parent": "32",
            "name": "镇江"
        },
        {
            "value": "3212",
            "parent": "32",
            "name": "泰州"
        },
        {
            "value": "3213",
            "parent": "32",
            "name": "宿迁"
        },
        {
            "value": "3301",
            "parent": "33",
            "name": "杭州"
        },
        {
            "value": "3302",
            "parent": "33",
            "name": "宁波"
        },
        {
            "value": "3303",
            "parent": "33",
            "name": "温州"
        },
        {
            "value": "3304",
            "parent": "33",
            "name": "嘉兴"
        },
        {
            "value": "3305",
            "parent": "33",
            "name": "湖州"
        },
        {
            "value": "3306",
            "parent": "33",
            "name": "绍兴"
        },
        {
            "value": "3307",
            "parent": "33",
            "name": "金华"
        },
        {
            "value": "3308",
            "parent": "33",
            "name": "衢州"
        },
        {
            "value": "3309",
            "parent": "33",
            "name": "舟山"
        },
        {
            "value": "3310",
            "parent": "33",
            "name": "台州"
        },
        {
            "value": "3311",
            "parent": "33",
            "name": "丽水"
        },
        {
            "value": "3401",
            "parent": "34",
            "name": "合肥"
        },
        {
            "value": "3402",
            "parent": "34",
            "name": "芜湖"
        },
        {
            "value": "3403",
            "parent": "34",
            "name": "蚌埠"
        },
        {
            "value": "3404",
            "parent": "34",
            "name": "淮南"
        },
        {
            "value": "3405",
            "parent": "34",
            "name": "马鞍山"
        },
        {
            "value": "3406",
            "parent": "34",
            "name": "淮北"
        },
        {
            "value": "3407",
            "parent": "34",
            "name": "铜陵"
        },
        {
            "value": "3408",
            "parent": "34",
            "name": "安庆"
        },
        {
            "value": "3410",
            "parent": "34",
            "name": "黄山"
        },
        {
            "value": "3411",
            "parent": "34",
            "name": "滁州"
        },
        {
            "value": "3412",
            "parent": "34",
            "name": "阜阳"
        },
        {
            "value": "3413",
            "parent": "34",
            "name": "宿州"
        },
        {
            "value": "3414",
            "parent": "34",
            "name": "巢湖"
        },
        {
            "value": "3415",
            "parent": "34",
            "name": "六安"
        },
        {
            "value": "3416",
            "parent": "34",
            "name": "亳州"
        },
        {
            "value": "3417",
            "parent": "34",
            "name": "池州"
        },
        {
            "value": "3418",
            "parent": "34",
            "name": "宣城"
        },
        {
            "value": "3501",
            "parent": "35",
            "name": "福州"
        },
        {
            "value": "3502",
            "parent": "35",
            "name": "厦门"
        },
        {
            "value": "3503",
            "parent": "35",
            "name": "莆田"
        },
        {
            "value": "3504",
            "parent": "35",
            "name": "三明"
        },
        {
            "value": "3505",
            "parent": "35",
            "name": "泉州"
        },
        {
            "value": "3506",
            "parent": "35",
            "name": "漳州"
        },
        {
            "value": "3507",
            "parent": "35",
            "name": "南平"
        },
        {
            "value": "3508",
            "parent": "35",
            "name": "龙岩"
        },
        {
            "value": "3509",
            "parent": "35",
            "name": "宁德"
        },
        {
            "value": "3601",
            "parent": "36",
            "name": "南昌"
        },
        {
            "value": "3602",
            "parent": "36",
            "name": "景德镇"
        },
        {
            "value": "3603",
            "parent": "36",
            "name": "萍乡"
        },
        {
            "value": "3604",
            "parent": "36",
            "name": "九江"
        },
        {
            "value": "3605",
            "parent": "36",
            "name": "新余"
        },
        {
            "value": "3606",
            "parent": "36",
            "name": "鹰潭"
        },
        {
            "value": "3607",
            "parent": "36",
            "name": "赣州"
        },
        {
            "value": "3608",
            "parent": "36",
            "name": "吉安"
        },
        {
            "value": "3609",
            "parent": "36",
            "name": "宜春"
        },
        {
            "value": "3610",
            "parent": "36",
            "name": "抚州"
        },
        {
            "value": "3611",
            "parent": "36",
            "name": "上饶"
        },
        {
            "value": "3701",
            "parent": "37",
            "name": "济南"
        },
        {
            "value": "3702",
            "parent": "37",
            "name": "青岛"
        },
        {
            "value": "3703",
            "parent": "37",
            "name": "淄博"
        },
        {
            "value": "3704",
            "parent": "37",
            "name": "枣庄"
        },
        {
            "value": "3705",
            "parent": "37",
            "name": "东营"
        },
        {
            "value": "3706",
            "parent": "37",
            "name": "烟台"
        },
        {
            "value": "3707",
            "parent": "37",
            "name": "潍坊"
        },
        {
            "value": "3708",
            "parent": "37",
            "name": "济宁"
        },
        {
            "value": "3709",
            "parent": "37",
            "name": "泰安"
        },
        {
            "value": "3710",
            "parent": "37",
            "name": "威海"
        },
        {
            "value": "3711",
            "parent": "37",
            "name": "日照"
        },
        {
            "value": "3712",
            "parent": "37",
            "name": "莱芜"
        },
        {
            "value": "3713",
            "parent": "37",
            "name": "临沂"
        },
        {
            "value": "3714",
            "parent": "37",
            "name": "德州"
        },
        {
            "value": "3715",
            "parent": "37",
            "name": "聊城"
        },
        {
            "value": "3716",
            "parent": "37",
            "name": "滨州"
        },
        {
            "value": "3717",
            "parent": "37",
            "name": "菏泽"
        },
        {
            "value": "4101",
            "parent": "41",
            "name": "郑州"
        },
        {
            "value": "4102",
            "parent": "41",
            "name": "开封"
        },
        {
            "value": "4103",
            "parent": "41",
            "name": "洛阳"
        },
        {
            "value": "4104",
            "parent": "41",
            "name": "平顶山"
        },
        {
            "value": "4105",
            "parent": "41",
            "name": "安阳"
        },
        {
            "value": "4106",
            "parent": "41",
            "name": "鹤壁"
        },
        {
            "value": "4107",
            "parent": "41",
            "name": "新乡"
        },
        {
            "value": "4108",
            "parent": "41",
            "name": "焦作"
        },
        {
            "value": "4109",
            "parent": "41",
            "name": "濮阳"
        },
        {
            "value": "4110",
            "parent": "41",
            "name": "许昌"
        },
        {
            "value": "4111",
            "parent": "41",
            "name": "漯河"
        },
        {
            "value": "4112",
            "parent": "41",
            "name": "三门峡"
        },
        {
            "value": "4113",
            "parent": "41",
            "name": "南阳"
        },
        {
            "value": "4114",
            "parent": "41",
            "name": "商丘"
        },
        {
            "value": "4115",
            "parent": "41",
            "name": "信阳"
        },
        {
            "value": "4116",
            "parent": "41",
            "name": "周口"
        },
        {
            "value": "4117",
            "parent": "41",
            "name": "驻马店"
        },
        {
            "value": "4201",
            "parent": "42",
            "name": "武汉"
        },
        {
            "value": "4202",
            "parent": "42",
            "name": "黄石"
        },
        {
            "value": "4203",
            "parent": "42",
            "name": "十堰"
        },
        {
            "value": "4205",
            "parent": "42",
            "name": "宜昌"
        },
        {
            "value": "4206",
            "parent": "42",
            "name": "襄樊"
        },
        {
            "value": "4207",
            "parent": "42",
            "name": "鄂州"
        },
        {
            "value": "4208",
            "parent": "42",
            "name": "荆门"
        },
        {
            "value": "4209",
            "parent": "42",
            "name": "孝感"
        },
        {
            "value": "4210",
            "parent": "42",
            "name": "荆州"
        },
        {
            "value": "4211",
            "parent": "42",
            "name": "黄冈"
        },
        {
            "value": "4212",
            "parent": "42",
            "name": "咸宁"
        },
        {
            "value": "4213",
            "parent": "42",
            "name": "随州"
        },
        {
            "value": "4228",
            "parent": "42",
            "name": "恩施土家族苗族自治州"
        },
        {
            "value": "4290",
            "parent": "42",
            "name": "省直辖行政单位"
        },
        {
            "value": "4301",
            "parent": "43",
            "name": "长沙"
        },
        {
            "value": "4302",
            "parent": "43",
            "name": "株洲"
        },
        {
            "value": "4303",
            "parent": "43",
            "name": "湘潭"
        },
        {
            "value": "4304",
            "parent": "43",
            "name": "衡阳"
        },
        {
            "value": "4305",
            "parent": "43",
            "name": "邵阳"
        },
        {
            "value": "4306",
            "parent": "43",
            "name": "岳阳"
        },
        {
            "value": "4307",
            "parent": "43",
            "name": "常德"
        },
        {
            "value": "4308",
            "parent": "43",
            "name": "张家界"
        },
        {
            "value": "4309",
            "parent": "43",
            "name": "益阳"
        },
        {
            "value": "4310",
            "parent": "43",
            "name": "郴州"
        },
        {
            "value": "4311",
            "parent": "43",
            "name": "永州"
        },
        {
            "value": "4312",
            "parent": "43",
            "name": "怀化"
        },
        {
            "value": "4313",
            "parent": "43",
            "name": "娄底"
        },
        {
            "value": "4331",
            "parent": "43",
            "name": "湘西土家族苗族自治州"
        },
        {
            "value": "4401",
            "parent": "44",
            "name": "广州"
        },
        {
            "value": "4402",
            "parent": "44",
            "name": "韶关"
        },
        {
            "value": "4403",
            "parent": "44",
            "name": "深圳"
        },
        {
            "value": "4404",
            "parent": "44",
            "name": "珠海"
        },
        {
            "value": "4405",
            "parent": "44",
            "name": "汕头"
        },
        {
            "value": "4406",
            "parent": "44",
            "name": "佛山"
        },
        {
            "value": "4407",
            "parent": "44",
            "name": "江门"
        },
        {
            "value": "4408",
            "parent": "44",
            "name": "湛江"
        },
        {
            "value": "4409",
            "parent": "44",
            "name": "茂名"
        },
        {
            "value": "4412",
            "parent": "44",
            "name": "肇庆"
        },
        {
            "value": "4413",
            "parent": "44",
            "name": "惠州"
        },
        {
            "value": "4414",
            "parent": "44",
            "name": "梅州"
        },
        {
            "value": "4415",
            "parent": "44",
            "name": "汕尾"
        },
        {
            "value": "4416",
            "parent": "44",
            "name": "河源"
        },
        {
            "value": "4417",
            "parent": "44",
            "name": "阳江"
        },
        {
            "value": "4418",
            "parent": "44",
            "name": "清远"
        },
        {
            "value": "4419",
            "parent": "44",
            "name": "东莞"
        },
        {
            "value": "4420",
            "parent": "44",
            "name": "中山"
        },
        {
            "value": "4451",
            "parent": "44",
            "name": "潮州"
        },
        {
            "value": "4452",
            "parent": "44",
            "name": "揭阳"
        },
        {
            "value": "4453",
            "parent": "44",
            "name": "云浮"
        },
        {
            "value": "4501",
            "parent": "45",
            "name": "南宁"
        },
        {
            "value": "4502",
            "parent": "45",
            "name": "柳州"
        },
        {
            "value": "4503",
            "parent": "45",
            "name": "桂林"
        },
        {
            "value": "4504",
            "parent": "45",
            "name": "梧州"
        },
        {
            "value": "4505",
            "parent": "45",
            "name": "北海"
        },
        {
            "value": "4506",
            "parent": "45",
            "name": "防城港"
        },
        {
            "value": "4507",
            "parent": "45",
            "name": "钦州"
        },
        {
            "value": "4508",
            "parent": "45",
            "name": "贵港"
        },
        {
            "value": "4509",
            "parent": "45",
            "name": "玉林"
        },
        {
            "value": "4521",
            "parent": "45",
            "name": "南宁地区"
        },
        {
            "value": "4522",
            "parent": "45",
            "name": "柳州地区"
        },
        {
            "value": "4524",
            "parent": "45",
            "name": "贺州地区"
        },
        {
            "value": "4526",
            "parent": "45",
            "name": "百色地区"
        },
        {
            "value": "4527",
            "parent": "45",
            "name": "河池地区"
        },
        {
            "value": "4601",
            "parent": "46",
            "name": "海南"
        },
        {
            "value": "4602",
            "parent": "46",
            "name": "海口"
        },
        {
            "value": "4603",
            "parent": "46",
            "name": "三亚"
        },
        {
            "value": "5101",
            "parent": "51",
            "name": "成都"
        },
        {
            "value": "5103",
            "parent": "51",
            "name": "自贡"
        },
        {
            "value": "5104",
            "parent": "51",
            "name": "攀枝花"
        },
        {
            "value": "5105",
            "parent": "51",
            "name": "泸州"
        },
        {
            "value": "5106",
            "parent": "51",
            "name": "德阳"
        },
        {
            "value": "5107",
            "parent": "51",
            "name": "绵阳"
        },
        {
            "value": "5108",
            "parent": "51",
            "name": "广元"
        },
        {
            "value": "5109",
            "parent": "51",
            "name": "遂宁"
        },
        {
            "value": "5110",
            "parent": "51",
            "name": "内江"
        },
        {
            "value": "5111",
            "parent": "51",
            "name": "乐山"
        },
        {
            "value": "5113",
            "parent": "51",
            "name": "南充"
        },
        {
            "value": "5114",
            "parent": "51",
            "name": "眉山"
        },
        {
            "value": "5115",
            "parent": "51",
            "name": "宜宾"
        },
        {
            "value": "5116",
            "parent": "51",
            "name": "广安"
        },
        {
            "value": "5117",
            "parent": "51",
            "name": "达州"
        },
        {
            "value": "5118",
            "parent": "51",
            "name": "雅安"
        },
        {
            "value": "5119",
            "parent": "51",
            "name": "巴中"
        },
        {
            "value": "5120",
            "parent": "51",
            "name": "资阳"
        },
        {
            "value": "5132",
            "parent": "51",
            "name": "阿坝藏族羌族自治州"
        },
        {
            "value": "5133",
            "parent": "51",
            "name": "甘孜藏族自治州"
        },
        {
            "value": "5134",
            "parent": "51",
            "name": "凉山彝族自治州"
        },
        {
            "value": "5201",
            "parent": "52",
            "name": "贵阳"
        },
        {
            "value": "5202",
            "parent": "52",
            "name": "六盘水"
        },
        {
            "value": "5203",
            "parent": "52",
            "name": "遵义"
        },
        {
            "value": "5204",
            "parent": "52",
            "name": "安顺"
        },
        {
            "value": "5222",
            "parent": "52",
            "name": "铜仁地区"
        },
        {
            "value": "5223",
            "parent": "52",
            "name": "黔西南布依族苗族自治"
        },
        {
            "value": "5224",
            "parent": "52",
            "name": "毕节地区"
        },
        {
            "value": "5226",
            "parent": "52",
            "name": "黔东南苗族侗族自治州"
        },
        {
            "value": "5227",
            "parent": "52",
            "name": "黔南布依族苗族自治州"
        },
        {
            "value": "5301",
            "parent": "53",
            "name": "昆明"
        },
        {
            "value": "5303",
            "parent": "53",
            "name": "曲靖"
        },
        {
            "value": "5304",
            "parent": "53",
            "name": "玉溪"
        },
        {
            "value": "5305",
            "parent": "53",
            "name": "保山"
        },
        {
            "value": "5321",
            "parent": "53",
            "name": "昭通地区"
        },
        {
            "value": "5323",
            "parent": "53",
            "name": "楚雄彝族自治州"
        },
        {
            "value": "5325",
            "parent": "53",
            "name": "红河哈尼族彝族自治州"
        },
        {
            "value": "5326",
            "parent": "53",
            "name": "文山壮族苗族自治州"
        },
        {
            "value": "5327",
            "parent": "53",
            "name": "思茅地区"
        },
        {
            "value": "5328",
            "parent": "53",
            "name": "西双版纳傣族自治州"
        },
        {
            "value": "5329",
            "parent": "53",
            "name": "大理白族自治州"
        },
        {
            "value": "5331",
            "parent": "53",
            "name": "德宏傣族景颇族自治州"
        },
        {
            "value": "5332",
            "parent": "53",
            "name": "丽江地区"
        },
        {
            "value": "5333",
            "parent": "53",
            "name": "怒江傈僳族自治州"
        },
        {
            "value": "5334",
            "parent": "53",
            "name": "迪庆藏族自治州"
        },
        {
            "value": "5335",
            "parent": "53",
            "name": "临沧地区"
        },
        {
            "value": "5401",
            "parent": "54",
            "name": "拉萨"
        },
        {
            "value": "5421",
            "parent": "54",
            "name": "昌都地区"
        },
        {
            "value": "5422",
            "parent": "54",
            "name": "山南地区"
        },
        {
            "value": "5423",
            "parent": "54",
            "name": "日喀则地区"
        },
        {
            "value": "5424",
            "parent": "54",
            "name": "那曲地区"
        },
        {
            "value": "5425",
            "parent": "54",
            "name": "阿里地区"
        },
        {
            "value": "5426",
            "parent": "54",
            "name": "林芝地区"
        },
        {
            "value": "6101",
            "parent": "61",
            "name": "西安"
        },
        {
            "value": "6102",
            "parent": "61",
            "name": "铜川"
        },
        {
            "value": "6103",
            "parent": "61",
            "name": "宝鸡"
        },
        {
            "value": "6104",
            "parent": "61",
            "name": "咸阳"
        },
        {
            "value": "6105",
            "parent": "61",
            "name": "渭南"
        },
        {
            "value": "6106",
            "parent": "61",
            "name": "延安"
        },
        {
            "value": "6107",
            "parent": "61",
            "name": "汉中"
        },
        {
            "value": "6108",
            "parent": "61",
            "name": "榆林"
        },
        {
            "value": "6109",
            "parent": "61",
            "name": "安康"
        },
        {
            "value": "6125",
            "parent": "61",
            "name": "商洛地区"
        },
        {
            "value": "6201",
            "parent": "62",
            "name": "兰州"
        },
        {
            "value": "6202",
            "parent": "62",
            "name": "嘉峪关"
        },
        {
            "value": "6203",
            "parent": "62",
            "name": "金昌"
        },
        {
            "value": "6204",
            "parent": "62",
            "name": "白银"
        },
        {
            "value": "6205",
            "parent": "62",
            "name": "天水"
        },
        {
            "value": "6221",
            "parent": "62",
            "name": "酒泉地区"
        },
        {
            "value": "6222",
            "parent": "62",
            "name": "张掖地区"
        },
        {
            "value": "6223",
            "parent": "62",
            "name": "武威地区"
        },
        {
            "value": "6224",
            "parent": "62",
            "name": "定西地区"
        },
        {
            "value": "6226",
            "parent": "62",
            "name": "陇南地区"
        },
        {
            "value": "6227",
            "parent": "62",
            "name": "平凉地区"
        },
        {
            "value": "6228",
            "parent": "62",
            "name": "庆阳地区"
        },
        {
            "value": "6229",
            "parent": "62",
            "name": "临夏回族自治州"
        },
        {
            "value": "6230",
            "parent": "62",
            "name": "甘南藏族自治州"
        },
        {
            "value": "6301",
            "parent": "63",
            "name": "西宁"
        },
        {
            "value": "6321",
            "parent": "63",
            "name": "海东地区"
        },
        {
            "value": "6322",
            "parent": "63",
            "name": "海北藏族自治州"
        },
        {
            "value": "6323",
            "parent": "63",
            "name": "黄南藏族自治州"
        },
        {
            "value": "6325",
            "parent": "63",
            "name": "海南藏族自治州"
        },
        {
            "value": "6326",
            "parent": "63",
            "name": "果洛藏族自治州"
        },
        {
            "value": "6327",
            "parent": "63",
            "name": "玉树藏族自治州"
        },
        {
            "value": "6328",
            "parent": "63",
            "name": "海西蒙古族藏族自治州"
        },
        {
            "value": "6401",
            "parent": "64",
            "name": "银川"
        },
        {
            "value": "6402",
            "parent": "64",
            "name": "石嘴山"
        },
        {
            "value": "6403",
            "parent": "64",
            "name": "吴忠"
        },
        {
            "value": "6422",
            "parent": "64",
            "name": "固原地区"
        },
        {
            "value": "6501",
            "parent": "65",
            "name": "乌鲁木齐"
        },
        {
            "value": "6502",
            "parent": "65",
            "name": "克拉玛依"
        },
        {
            "value": "6521",
            "parent": "65",
            "name": "吐鲁番地区"
        },
        {
            "value": "6522",
            "parent": "65",
            "name": "哈密地区"
        },
        {
            "value": "6523",
            "parent": "65",
            "name": "昌吉回族自治州"
        },
        {
            "value": "6527",
            "parent": "65",
            "name": "博尔塔拉蒙古自治州"
        },
        {
            "value": "6528",
            "parent": "65",
            "name": "巴音郭楞蒙古自治州"
        },
        {
            "value": "6529",
            "parent": "65",
            "name": "阿克苏地区"
        },
        {
            "value": "6530",
            "parent": "65",
            "name": "克孜勒苏柯尔克孜自治"
        },
        {
            "value": "6531",
            "parent": "65",
            "name": "喀什地区"
        },
        {
            "value": "6532",
            "parent": "65",
            "name": "和田地区"
        },
        {
            "value": "6540",
            "parent": "65",
            "name": "伊犁哈萨克自治州"
        },
        {
            "value": "6541",
            "parent": "65",
            "name": "伊犁地区"
        },
        {
            "value": "6542",
            "parent": "65",
            "name": "塔城地区"
        },
        {
            "value": "6543",
            "parent": "65",
            "name": "阿勒泰地区"
        },
        {
            "value": "6590",
            "parent": "65",
            "name": "省直辖行政单位"
        },
        {
            "value": "7101",
            "parent": "71",
            "name": "台湾"
        },
        {
            "value": "8101",
            "parent": "81",
            "name": "香港特区"
        },
        {
            "value": "9101",
            "parent": "91",
            "name": "澳门特区"
        },
        {
            "value": "110101",
            "parent": "11",
            "name": "东城区"
        },
        {
            "value": "110102",
            "parent": "11",
            "name": "西城区"
        },
        {
            "value": "110105",
            "parent": "11",
            "name": "朝阳区"
        },
        {
            "value": "110106",
            "parent": "11",
            "name": "丰台区"
        },
        {
            "value": "110107",
            "parent": "11",
            "name": "石景山区"
        },
        {
            "value": "110108",
            "parent": "11",
            "name": "海淀区"
        },
        {
            "value": "110109",
            "parent": "11",
            "name": "门头沟区"
        },
        {
            "value": "110111",
            "parent": "11",
            "name": "房山区"
        },
        {
            "value": "110112",
            "parent": "11",
            "name": "通州区"
        },
        {
            "value": "110113",
            "parent": "11",
            "name": "顺义区"
        },
        {
            "value": "110114",
            "parent": "11",
            "name": "昌平区"
        },
        {
            "value": "110224",
            "parent": "11",
            "name": "大兴区"
        },
        {
            "value": "110226",
            "parent": "11",
            "name": "平谷区"
        },
        {
            "value": "110227",
            "parent": "11",
            "name": "怀柔区"
        },
        {
            "value": "110228",
            "parent": "11",
            "name": "密云区"
        },
        {
            "value": "110229",
            "parent": "11",
            "name": "延庆区"
        },
        {
            "value": "120101",
            "parent": "12",
            "name": "和平区"
        },
        {
            "value": "120102",
            "parent": "12",
            "name": "河东区"
        },
        {
            "value": "120103",
            "parent": "12",
            "name": "河西区"
        },
        {
            "value": "120104",
            "parent": "12",
            "name": "南开区"
        },
        {
            "value": "120105",
            "parent": "12",
            "name": "河北区"
        },
        {
            "value": "120106",
            "parent": "12",
            "name": "红桥区"
        },
        {
            "value": "120107",
            "parent": "12",
            "name": "滨海新区"
        },
        {
            "value": "120110",
            "parent": "12",
            "name": "东丽区"
        },
        {
            "value": "120111",
            "parent": "12",
            "name": "西青区"
        },
        {
            "value": "120112",
            "parent": "12",
            "name": "津南区"
        },
        {
            "value": "120113",
            "parent": "12",
            "name": "北辰区"
        },
        {
            "value": "120114",
            "parent": "12",
            "name": "武清区"
        },
        {
            "value": "120221",
            "parent": "12",
            "name": "宁河区"
        },
        {
            "value": "120223",
            "parent": "12",
            "name": "静海区"
        },
        {
            "value": "120224",
            "parent": "12",
            "name": "宝坻区"
        },
        {
            "value": "120225",
            "parent": "12",
            "name": "蓟州区"
        },
        {
            "value": "310101",
            "parent": "31",
            "name": "黄浦区"
        },
        {
            "value": "310104",
            "parent": "31",
            "name": "徐汇区"
        },
        {
            "value": "310105",
            "parent": "31",
            "name": "长宁区"
        },
        {
            "value": "310106",
            "parent": "31",
            "name": "静安区"
        },
        {
            "value": "310107",
            "parent": "31",
            "name": "普陀区"
        },
        {
            "value": "310109",
            "parent": "31",
            "name": "虹口区"
        },
        {
            "value": "310110",
            "parent": "31",
            "name": "杨浦区"
        },
        {
            "value": "310112",
            "parent": "31",
            "name": "闵行区"
        },
        {
            "value": "310113",
            "parent": "31",
            "name": "宝山区"
        },
        {
            "value": "310114",
            "parent": "31",
            "name": "嘉定区"
        },
        {
            "value": "310115",
            "parent": "31",
            "name": "浦东新区"
        },
        {
            "value": "310116",
            "parent": "31",
            "name": "金山区"
        },
        {
            "value": "310117",
            "parent": "31",
            "name": "松江区"
        },
        {
            "value": "310118",
            "parent": "31",
            "name": "青浦区"
        },
        {
            "value": "310226",
            "parent": "31",
            "name": "奉贤区"
        },
        {
            "value": "310230",
            "parent": "31",
            "name": "崇明区"
        },
        {
            "value": "500101",
            "parent": "50",
            "name": "万州区"
        },
        {
            "value": "500102",
            "parent": "50",
            "name": "涪陵区"
        },
        {
            "value": "500103",
            "parent": "50",
            "name": "渝中区"
        },
        {
            "value": "500104",
            "parent": "50",
            "name": "大渡口区"
        },
        {
            "value": "500105",
            "parent": "50",
            "name": "江北区"
        },
        {
            "value": "500106",
            "parent": "50",
            "name": "沙坪坝区"
        },
        {
            "value": "500107",
            "parent": "50",
            "name": "九龙坡区"
        },
        {
            "value": "500108",
            "parent": "50",
            "name": "南岸区"
        },
        {
            "value": "500109",
            "parent": "50",
            "name": "北碚区"
        },
        {
            "value": "500110",
            "parent": "50",
            "name": "万盛区"
        },
        {
            "value": "500111",
            "parent": "50",
            "name": "双桥区"
        },
        {
            "value": "500112",
            "parent": "50",
            "name": "渝北区"
        },
        {
            "value": "500113",
            "parent": "50",
            "name": "巴南区"
        },
        {
            "value": "500114",
            "parent": "50",
            "name": "黔江区"
        },
        {
            "value": "500221",
            "parent": "50",
            "name": "长寿区"
        },
        {
            "value": "500222",
            "parent": "50",
            "name": "綦江区"
        },
        {
            "value": "500223",
            "parent": "50",
            "name": "潼南区"
        },
        {
            "value": "500224",
            "parent": "50",
            "name": "铜梁区"
        },
        {
            "value": "500225",
            "parent": "50",
            "name": "大足区"
        },
        {
            "value": "500226",
            "parent": "50",
            "name": "荣昌区"
        },
        {
            "value": "500227",
            "parent": "50",
            "name": "璧山区"
        },
        {
            "value": "500228",
            "parent": "50",
            "name": "梁平区"
        },
        {
            "value": "500229",
            "parent": "50",
            "name": "城口县"
        },
        {
            "value": "500230",
            "parent": "50",
            "name": "丰都县"
        },
        {
            "value": "500231",
            "parent": "50",
            "name": "垫江县"
        },
        {
            "value": "500232",
            "parent": "50",
            "name": "武隆区"
        },
        {
            "value": "500233",
            "parent": "50",
            "name": "忠  县"
        },
        {
            "value": "500234",
            "parent": "50",
            "name": "开州区"
        },
        {
            "value": "500235",
            "parent": "50",
            "name": "云阳县"
        },
        {
            "value": "500236",
            "parent": "50",
            "name": "奉节县"
        },
        {
            "value": "500237",
            "parent": "50",
            "name": "巫山县"
        },
        {
            "value": "500238",
            "parent": "50",
            "name": "巫溪县"
        },
        {
            "value": "500240",
            "parent": "50",
            "name": "石柱土家族自治县"
        },
        {
            "value": "500241",
            "parent": "50",
            "name": "秀山土家族苗族自治县"
        },
        {
            "value": "500242",
            "parent": "50",
            "name": "酉阳土家族苗族自治县"
        },
        {
            "value": "500243",
            "parent": "50",
            "name": "彭水苗族土家族自治县"
        },
        {
            "value": "500381",
            "parent": "50",
            "name": "江津区"
        },
        {
            "value": "500382",
            "parent": "50",
            "name": "合川区"
        },
        {
            "value": "500383",
            "parent": "50",
            "name": "永川区"
        },
        {
            "value": "500384",
            "parent": "50",
            "name": "南川区"
        }]';

     }
 }
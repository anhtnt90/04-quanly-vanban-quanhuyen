//ISA-Infomatics Technology, Hanoi, Vietnam
//Thanks to Zapatec Corporation
// ** I18N

// Calendar EN language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Encoding: any
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Zapatec.Calendar._DN = new Array
("chủ nhật",
 "thứ hai",
 "thứ ba",
 "thứ tư",
 "thứ năm",
 "thứ sáu",
 "thứ bảy",
 "chủ nhật");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Zapatec.Calendar._SDN_len = N; // short day name length
//   Zapatec.Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Zapatec.Calendar._SDN = new Array
("CN",
 "Hai",
 "Ba",
 "Tư",
 "Năm",
 "Sáu",
 "Bảy",
 "CN");

// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Zapatec.Calendar._FD = 0;

// full month names
Zapatec.Calendar._MN = new Array
("Tháng Giêng",
 "Tháng Hai",
 "Tháng Ba",
 "Tháng Tư",
 "Tháng Năm",
 "Tháng Sáu",
 "Tháng Bảy",
 "Tháng Tám",
 "Tháng Chín",
 "Tháng Mười",
 "Tháng M.Một",
 "Tháng M.Hai");

// short month names
Zapatec.Calendar._SMN = new Array
("Tháng Giêng",
 "Tháng Hai",
 "Tháng Ba",
 "Tháng Tư",
 "Tháng Năm",
 "Tháng Sáu",
 "Tháng Bảy",
 "Tháng Tám",
 "Tháng Chín",
 "Tháng Mười",
 "Tháng M.Một",
 "Tháng M.Hai");

// tooltips
Zapatec.Calendar._TT_en = Zapatec.Calendar._TT = {};
Zapatec.Calendar._TT["INFO"] = "About the calendar";

Zapatec.Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) zapatec.com 2002-2004\n" + // don't translate this this ;-)
"For latest version visit: http://www.zapatec.com/\n" +
"\n\n" +
"Date selection:\n" +
"- Use the \xab, \xbb buttons to select year\n" +
"- Use the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" +
"- Hold mouse button on any of the above buttons for faster selection.";
Zapatec.Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

Zapatec.Calendar._TT["PREV_YEAR"] = "Năm trước";
Zapatec.Calendar._TT["PREV_MONTH"] = "Tháng trước";
Zapatec.Calendar._TT["GO_TODAY"] = "Chuyển đến hôm nay";
Zapatec.Calendar._TT["NEXT_MONTH"] = "Tháng kế tiếp";
Zapatec.Calendar._TT["NEXT_YEAR"] = "Năm kế tiếp";
Zapatec.Calendar._TT["SEL_DATE"] = "Chọn ngày date";
Zapatec.Calendar._TT["DRAG_TO_MOVE"] = "Giữ và kéo để di chuyển";
Zapatec.Calendar._TT["PART_TODAY"] = " (hôm nay)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Zapatec.Calendar._TT["DAY_FIRST"] = "Hiển thị %s trước";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Zapatec.Calendar._TT["WEEKEND"] = "0,6";

Zapatec.Calendar._TT["CLOSE"] = "Đóng";
Zapatec.Calendar._TT["TODAY"] = "Hôm nay";
Zapatec.Calendar._TT["TIME_PART"] = "(Shift-) Nhấn hoặc kéo để thay đổi giá trị";

// date formats
Zapatec.Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Zapatec.Calendar._TT["TT_DATE_FORMAT"] = "Ngày %e %b";

Zapatec.Calendar._TT["WK"] = "Tuần";
Zapatec.Calendar._TT["TIME"] = "Giờ:";

Zapatec.Calendar._TT["E_RANGE"] = "Ngoài phạm vi quản lý";

/* Preserve data */
	if(Zapatec.Calendar._DN) Zapatec.Calendar._TT._DN = Zapatec.Calendar._DN;
	if(Zapatec.Calendar._SDN) Zapatec.Calendar._TT._SDN = Zapatec.Calendar._SDN;
	if(Zapatec.Calendar._SDN_len) Zapatec.Calendar._TT._SDN_len = Zapatec.Calendar._SDN_len;
	if(Zapatec.Calendar._MN) Zapatec.Calendar._TT._MN = Zapatec.Calendar._MN;
	if(Zapatec.Calendar._SMN) Zapatec.Calendar._TT._SMN = Zapatec.Calendar._SMN;
	if(Zapatec.Calendar._SMN_len) Zapatec.Calendar._TT._SMN_len = Zapatec.Calendar._SMN_len;
	Zapatec.Calendar._DN = Zapatec.Calendar._SDN = Zapatec.Calendar._SDN_len = Zapatec.Calendar._MN = Zapatec.Calendar._SMN = Zapatec.Calendar._SMN_len = null

const gsm7bitChars = "@£$¥èéùìòÇ\\nØø\\rÅåΔ_ΦΓΛΩΠΨΣΘΞÆæßÉ !\\\"#¤%&'()*+,-./0123456789:;<=>?¡ABCDEFGHIJKLMNOPQRSTUVWXYZÄÖÑÜ§¿abcdefghijklmnopqrstuvwxyzäöñüà";
const gsm7bitExChar = "\\^{}\\\\\\[~\\]|€";
const gsm7bitRegExp =  RegExp("^[" + gsm7bitChars + "]*$");
const gsm7bitExRegExp = RegExp("^[" + gsm7bitChars + gsm7bitExChar + "]*$");
const gsm7bitExOnlyRegExp = RegExp("^[\\" + gsm7bitExChar + "]*$");
const GSM_7BIT = 'GSM_7BIT';
const GSM_7BIT_EX = 'GSM_7BIT_EX';
const UTF16 = 'UTF16';
const MAX_SMS_PARTS = 6;

const messageLength = {
	GSM_7BIT: 160,
	GSM_7BIT_EX: 160,
	UTF16: 70
};

const multiMessageLength = {
	GSM_7BIT: 153,
	GSM_7BIT_EX: 153,
	UTF16: 67
}

module.exports = {
	template: `<textarea class="form-control" placeholder="Type Your Message Here" @input="here($event.target.value)"></textarea>`,

	data: function () {
		return {
			count: {
				encoding: null,
				length: 0,
				per_message: 0,
				remaining: 0,
				char_type: '',
				messages: 0,
				text: '',
				max: 918
			}
		}
	},

	methods: {
		detectEncoding (text) {
			switch (false) {
				case text.match(gsm7bitRegExp) == null:
					return GSM_7BIT;
				case text.match(gsm7bitExRegExp) == null:
					return GSM_7BIT_EX;
				default:
					return UTF16;
			}
		},

		countGsm7bitEx (text) {
			var char2, chars;

			chars = (function () {
				var _i, _len, _results;
				_results = [];

				for (_i = 0, _len = text.length; _i < _len; _i++) {
					char2 = text[_i];
					if (char2.match(gsm7bitExOnlyRegExp) != null) {
						_results.push(char2);
					}
				}

				return _results;
			}).call(this);

			return chars.length;
		},

		here (text) {
			var count, encoding, length, messages, per_message, remaining;
			encoding = this.detectEncoding(text);

			length = text.length;

			if (encoding === GSM_7BIT_EX) {
				length += this.countGsm7bitEx(text);
			}

			per_message = messageLength[encoding];

			if (length > per_message) {
				per_message = multiMessageLength[encoding];
			}

			messages = Math.ceil(length / per_message);
			remaining = (per_message * messages) - length;

			if (remaining == 0 && messages == 0) {
				remaining = per_message;
			}

			this.count.encoding = encoding;
			this.count.length = length;
			this.count.per_message = per_message;
			this.count.remaining = remaining;
			this.count.messages = messages
			this.count.char_type = text != '' ? (encoding == UTF16 ? 'Unicode' : '') : '';
			this.count.text = text;
			this.count.max = multiMessageLength[encoding] * MAX_SMS_PARTS;
			this.$emit('input', this.count);
		}
	}
}
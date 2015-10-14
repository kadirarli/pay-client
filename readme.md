## Pay Client

Firmamızda birden fazla ödeme yöntemi kullanılmaktadır. Paypal, Payu, PayTrek gibi sanal POS servisi veren banka ve ödeme servisi sağlayıcıların apilerini ortak bir arayüz üzerinden basitçe kullanılabilmesini sağlayan bir proje tasarlayınız. 
Ödeme sayfası aşağıdaki gibi olacaktır: 
Name: İsim ve Soyisim.
Payment Gateway: Ödeme yapılacak araç (Paypal, PayU, Paytrek) Value: 
Ödenecek miktar Kur: EUR, USD, TRY değerleri seçilebilir.
Bu kütüphanedeki her ödeme tipi aşağıdaki temel fonksiyonlara sahip olacaktır; 
	•	checkCurrency(): Ödeme sayfasında gateway aracının kabul ettiği döviz kurundan farklı bir döviz kuru seçilerek ödeme yapılması istenirse, pay() metodundan önce sahip olduğu döviz kuru farkı oranı ile çarpılıp ödeme gerçekleştirelecek. (Örn: PayPal => default kur: EUR, farklı kur ile ödeme yapıldığı zaman *1.08 ) Detay için açıkalamalara bakınız.  
	•	pay(): Ödeme arabiriminin API’sine bilgiler gönderilecek. Her ödeme türünün sınıfının ilgili metodunun içi sadece success dönecek şekilde boş olabilir.  
	•	sendVoucher(): Ödeme fatura bilgisi muhabese departmanına mail atılacak. Özet olarak genel iş akışı şu şekilde olacaktır:  1. Döviz kuru farkı varsa, belirlediğimiz orana göre yeni ödeme tutarını belirle. (Bkz Tablo 1) 2. Aracın API'sine bağlan, ödeme işlemini gerçekleştir. 3. Fatura bilgisini accounting@testtest.com'a mail at. (Bkz Tablo 2)  
￼ ￼ 
Açıklamalar: 
	•	Herhangi bir framework zorunlu olmamasına rağmen tüm yapı nesne yönelimli olmalıdır.  
	•	Herhangi bir ödeme aracı anlık olarak deaktif edilebilir. Bu durumda ödeme sayfasında seçilememeli ve ödeme  anında hata vermelidir.  
	•	Her ödeme aracının varsayılan olarak kabul ettiği bir kur vardır. Ödeme sayfasından farklı bir kur seçildiği zaman  oluşabilecek zararı önleyebilmek için bir kur farkı oranı belirlenmiştir. Bundan dolayı checkCurrency() adlı bir method oluşturulmuştur. Bu metodda her ödeme gerçekleşmeden önce, ödeme aracının kur farkı oranına bakılarak yeni değer hesaplanmalı ve ödeme bu yeni değerle gerçekleşmelidir.  
	•	Ödeme aracının Kur Farkı kolayca değiştirebilir olmalıdır.. Bu durumda yeni kur farkı oranı üzerinden hesaplama yapılabilmelidir. Her ödeme aracının varsayılan kuru ve fark oranı aşağıdaki gibidir.  Tablo 1: Ödeme aracının varsayılan kurları ve kur farkı oranları Örnek: Ödeme aracı PayPal seçiliyor. Ancak kur olarak 100 TRY seçiliyor. Bu durumda ödemeye 100 * 1.08 ile devam etmeli  
	•	Atılacak fatura bilgisi mail’i aşağıdaki gibi olacaktır. Yine bu template değiştirilebilir olabilir.
<table>
	<tr>
		<td>Gateway</td>
		<td>Varsayılan Kur</td>
		<td>Kur Farkı</td>
	</tr>
	<tr>
		<td>Paypal</td>
		<td>EUR</td>
		<td>1.08</td>
	</tr>
	<tr>
		<td>PayU</td>
		<td>TRY</td>
		<td>1.12</td>
	</tr>
	<tr>
		<td>PayTrek</td>
		<td>USD</td>
		<td>1.10</td>
	</tr>
</table>

Subject: Payment successful 
Body: A new payment is made. Details are below: Name: [NAME] Amount: [VALUE] [CURRENCY] Time: [CURRENT_DATE_TIME] 
Tablo 2: Fatura bilgisi maili. 

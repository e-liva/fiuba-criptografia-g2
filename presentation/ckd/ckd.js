
function CKD() {
	var
		/** @struct */ last={mykey:-1,mytimeStamp:0},
		data={};

	function insert(ok,data,delta,completekey) {
			if (ok&&delta<800) {
				if (data[completekey]==undefined) data[completekey]={mu:0,musq:0,n:0};
				data[completekey].mu+=delta;
				data[completekey].musq+=delta*delta;
				data[completekey].n++;
			}

	}

	function triggerEvent(key,timeStamp) {
		var
			completekey=(last.mykey<<10)|key,
			delta=timeStamp-last.mytimeStamp;

		insert(last.mykey!=-1,data,delta,completekey);
		last={mykey:key,mytimeStamp:timeStamp};
	}

	//Public methods
	this['listen']=function($el) {
		if (!$el['jQuery']) $el=$($el);
		$el['keydown'](function(e){
			triggerEvent(e['keyCode']*2|1,e['timeStamp']);
		})['keyup'](function(e){
			triggerEvent(e['keyCode']*2  ,e['timeStamp']);
		});
		return this;
	}
	this['clean']=function() {
		data={};
		return this;
	}
	this['get']=function() {
		var key,i,out='',keys=[],priorKey=0;//,comma='';
		for (key in data) {
			keys.push(key/1);
		}
		keys.sort(function(a,b){return a-b});
		for (i in keys) {
			var k=keys[i];
			//out+=comma+radix(k-priorKey)+'a'+radix(data[k].n)+'a'+radix(data[k].musq)+'a'+radix(data[k].mu);
			out+=radix2(k-priorKey)+radix2(data[k].n)+radix2(data[k].musq)+radix2(data[k].mu);
			//comma='a';
			priorKey=k;
		}
		return out;
	}

	function radix(number) {
		if (number<0) return "?";
		var out='',encode="1234567890qwertyuiopsdfghjklzxcvbnm=QWERTYUIOPASDFGHJKLZXCVBNM()";
		while (number) {
			out+=encode.charAt(number & 63);
			number=number>>6;
		}
		return out;
	}
	function radix2(number) {
		var
			out='';
		/** @const */ var
			encode1="0124689qwertyuiopsdfgASDFGHJKLZX",
			encode2="357ahjklzxcvbnmQWERTYUIOPCVBNM #";
		while (number>31) {
			out+=encode1.charAt(number & 31);
			number=number>>5;
		}
		return out+encode2.charAt(number);
	}

/*************************\
* Online Training Feature *
\*************************/

	var ot={};
	this['onlineTrain']=function(id_dimension,dimension_data) {
		for (var id_entity in dimension_data) {
			for (var k in dimension_data[id_entity]) {
				var
					mean=dimension_data[id_entity][k][0],
					variance=dimension_data[id_entity][k][1]+1,
					gamma=new Gamma(mean*mean/variance,variance/mean);
				gamma.mu=mean;
				dimension_data[id_entity][k]=gamma;
			}
		}
		ot[id_dimension]=dimension_data;
	}
	this['onlineCheck']=function(id_dimension) {
		var dimension_data=ot[id_dimension],result={};
		for (var id_entity in dimension_data) {
			var entity=dimension_data[id_entity],sum=0,total=0;
			for (var k in data) {
				var gamma=entity[k];
				if (gamma!=undefined) {
					var delta=gamma.cdf(data[k].mu)-gamma.cdf(gamma.mu);
					sum+=delta*delta;
					total++;
				}
			}
			if (total) result[id_entity]=sum/total;
		}
		return result;
	}






/************************\
* Gamma distribution API *
\************************/


	function Gamma(attr_a,attr_b) {
		var log=Math.log,exp=Math.exp,abs=Math.abs,sqrt=Math.sqrt;

		function LogGamma(Z) {
			var S=1+76.18009173/Z-86.50532033/(Z+1)+24.01409822/(Z+2)-1.231739516/(Z+3)+.00120858003/(Z+4)-.00000536382/(Z+5);
			return (Z-.5)*log(Z+4.5)-(Z+4.5)+log(S*2.50662827465);
		}
		
		function Gcf(X,A) {        // Good for X>A+1
				var A0=0;
				var B0=1;
				var A1=1;
				var B1=X;
				var AOLD=0;
				var N=0;
				while (abs((A1-AOLD)/A1)>.00001) {
					AOLD=A1;
					N=N+1;
					A0=A1+(N-A)*A0;
					B0=B1+(N-A)*B0;
					A1=X*A0+N*A1;
					B1=X*B0+N*B1;
					A0=A0/B1;
					B0=B0/B1;
					A1=A1/B1;
					B1=1;
				}
				var Prob=exp(A*log(X)-X-LogGamma(A))*A1;
			return 1-Prob
		}
		
		function Gser(X,A) {        // Good for X<A+1.
				var T9=1/A;
				var G=T9;
				var I=1;
				while (T9>G*.00001) {
					T9=T9*X/(A+I);
					G=G+T9;
					I=I+1;
				}
				G=G*exp(A*log(X)-X-LogGamma(A));
		    return G
		}
		
		function normalcdf(X){   //HASTINGS.  MAX ERROR = .000001
			var T=1/(1+.2316419*abs(X));
			var D=.3989423*exp(-X*X/2);
			var Prob=D*T*(.3193815+T*(-.3565638+T*(1.781478+T*(-1.821256+T*1.330274))));
			if (X>0) {
				Prob=1-Prob
			}
			return Prob
		}
		
		function Gammacdf(x,a) {
			var GI,z,y,b1,phiz,w,b2,u;
			if (x<=0) {
				GI=0
			} else if (a>200) {
				z=(x-a)/sqrt(a)
				y=normalcdf(z)
				b1=2/sqrt(a)
				phiz=.39894228*exp(-z*z/2)
				w=y-b1*(z*z-1)*phiz/6  //Edgeworth1
				b2=6/a
				u=3*b2*(z*z-3)+b1*b1*(z^4-10*z*z+15)
				GI=w-phiz*z*u/72        //Edgeworth2
			} else if (x<a+1) {
				GI=Gser(x,a)
			} else {
				GI=Gcf(x,a)
			}
			return GI
		}
		
		function compute(X,A,B) {
			return Gammacdf(X/B,A/1)
		}
	
		this.cdf=function(x) {
			return compute(x,attr_a,attr_b);
		}
	}
}

window['CKD']=CKD;

package wmi.appl.com;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URI;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.conn.params.ConnManagerParams;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

	 
public class CustomHttpClient {
   /** The time it takes for our client to timeout */
    public static final int HTTP_TIMEOUT = 30 * 1000; // milliseconds

    /** Single instance of our HttpClient */
    private static HttpClient mHttpClient;
    
    private static String url="http://fajarlestari.sytes.net:2000/salesmonitor/file/";
    //private static String url ="http://186.132.0.23/slo/";
    //	private static String url ="http://10.0.2.2/salesmonitor/file/";
    
    /**
     * Get our single instance of our HttpClient object.
     *
     * @return an HttpClient object with connection parameters set
     */
    private static HttpClient getHttpClient() {
        if (mHttpClient == null) {
            mHttpClient = new DefaultHttpClient();
            final HttpParams params = mHttpClient.getParams();
            HttpConnectionParams.setConnectionTimeout(params, HTTP_TIMEOUT);
            HttpConnectionParams.setSoTimeout(params, HTTP_TIMEOUT);
            ConnManagerParams.setTimeout(params, HTTP_TIMEOUT);
        }
        return mHttpClient;
    }
 
    /**
     * Performs an HTTP Post request to the specified url with the
     * specified parameters.
     *
     * @param url The web address to post the request to
     * @param postParameters The parameters to send via the request
     * @return The result of the request
     * @throws Exception
     */
    public static String executeHttpPost(String urlpost, ArrayList<NameValuePair> postParameters) throws Exception {
        
    	BufferedReader in = null;
        String urlkirim = url + urlpost;
    	
        try {
            HttpClient client = getHttpClient();
            HttpPost request = new HttpPost(urlkirim);
	            UrlEncodedFormEntity formEntity = new UrlEncodedFormEntity(postParameters);
	            
	           // Log.v("coba", postParameters.toString()); 
	            
            request.setEntity(formEntity);
            HttpResponse response = client.execute(request);
            in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
 
            StringBuffer sb = new StringBuffer("");
            String line = "";
            String NL = System.getProperty("line.separator");
            while ((line = in.readLine()) != null) {
                sb.append(line + NL);
            }
            in.close();
 
            String result = sb.toString();
            return result;
        } finally {
            if (in != null) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
	    }
    
    public static String executeHttpPost_JsonParse(String url, ArrayList<NameValuePair> postParameters) throws Exception {
        BufferedReader in = null;
        try {
            HttpClient client = getHttpClient();
            HttpPost request = new HttpPost(url);
	            UrlEncodedFormEntity formEntity = new UrlEncodedFormEntity(postParameters);
	            
	         String result = "";
	            
            request.setEntity(formEntity);
            HttpResponse response = client.execute(request);
            
            result = request(response);
           
            return result;
        } finally {
            if (in != null) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
	    }
    
    /**
     * Performs an HTTP GET request to the specified url.
     *
     * @param url The web address to post the request to
     * @return The result of the request
     * @throws Exception
     */
    public static String executeHttpGet(String url) throws Exception {
        BufferedReader in = null;
        try {
            HttpClient client = getHttpClient();
            HttpGet request = new HttpGet();
            request.setURI(new URI(url));
            HttpResponse response = client.execute(request);
            in = new BufferedReader(new InputStreamReader(response.getEntity().getContent()));
 
            StringBuffer sb = new StringBuffer("");
            String line = "";
            String NL = System.getProperty("line.separator");
            while ((line = in.readLine()) != null) {
                sb.append(line + NL);
            }
            in.close();
 
            String result = sb.toString();
            return result;
        } finally {
            if (in != null) {
                try {
                    in.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    public static String getRequest(String NUrlget, List<NameValuePair> params){
    		
    	 	InputStream is;
    		String sret="";
    		String urlGet= url + NUrlget;	
    	   
    	//	String paramString = URLEncodedUtils.format(params, "utf-8");          
         //   urlGet += "?" + paramString;  
	       
         //   Log.v("path", urlGet);
            
    		try {
				
    			HttpClient client = new DefaultHttpClient();
    		       // HttpGet request = new HttpGet(urlGet);
    	            HttpPost httpPost = new HttpPost(urlGet);
    	            httpPost.setEntity(new UrlEncodedFormEntity(params));
    	            
    		        try{
    		          HttpResponse response = client.execute(httpPost);
    		          HttpEntity entity = response.getEntity();
    		          
    		          is = entity.getContent();
    		          
    		          sret = convertStreamToString(is);
    		          
    		        //  Log.v("sret", sret);
    		          
    		        }catch(Exception ex){
    		        	sret="error"; 
    		        }
    			
			} catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
			}
    		
            
	        return sret;
	 
	    }
    
    public static String convertStreamToString(InputStream is) {
        String line = "";
        StringBuilder total = new StringBuilder();
        BufferedReader rd = new BufferedReader(new InputStreamReader(is));
        try {
            while ((line = rd.readLine()) != null) {
                total.append(line);
            }
        } catch (Exception e) {
        	e.printStackTrace();
        	//Toast.makeText(this, "Stream Exception", Toast.LENGTH_SHORT).show();
        }
    return total.toString();
    }
    
    public static String request(HttpResponse response){
        String result = "";
        try{
            InputStream in = response.getEntity().getContent();
            BufferedReader reader = new BufferedReader(new InputStreamReader(in,"iso-8859-1"), 8);
            
            StringBuilder str = new StringBuilder();
            String line = null;
            while((line = reader.readLine()) != null){
                str.append(line + "\n");
            }
            in.close();
            result = str.toString();
        }catch(Exception ex){
        	
            result = "Error";
        }
        return result;
    }

    
    
}

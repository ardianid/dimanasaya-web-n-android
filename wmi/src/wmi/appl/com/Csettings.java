package wmi.appl.com;

import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.Spinner;
import android.widget.Toast;

public class Csettings extends Activity {

	private DBAdapter dba=null;
	private SQLiteDatabase db = null; 
	
	private static String url= "setutility.php";
	
	EditText tpemilik;
	EditText ttelp;
	EditText tjarak;
	
	CheckBox csms;
	CheckBox ctelp;
	
	Spinner cbjenis;
	
	String namauser;
	
	String jenisbayar[]={"Jarak","Waktu"};
	
	ImageButton btrubahpwd;
	ImageButton btsimpan;
	ImageButton btcancel;
	
	Integer jmler=0;
    Integer jmlok=0;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.fsetting);
		
		dba = new DBAdapter(this);
		
		tpemilik = (EditText)findViewById(R.id.tpemilik);
		ttelp = (EditText)findViewById(R.id.ttelp);
		
		tjarak = (EditText)findViewById(R.id.tjarak);
		
		csms = (CheckBox)findViewById(R.id.csms);
		ctelp = (CheckBox)findViewById(R.id.ctelp);
		
		cbjenis = (Spinner)findViewById(R.id.cbjnismonitor);
		
		ArrayAdapter<String> aa=new ArrayAdapter<String>(this,android.R.layout.simple_spinner_item,jenisbayar);
    	cbjenis.setAdapter(aa);
		
		btrubahpwd = (ImageButton)findViewById(R.id.btrubahpwd);
		btsimpan = (ImageButton)findViewById(R.id.btsimpan);
		btcancel = (ImageButton)findViewById(R.id.btcancel);
		
		btrubahpwd.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				showDialog(1);
			}
		});
		
		
		btsimpan.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				simpan_data();
			}
		});
		
		
		btcancel.setOnClickListener(new Button.OnClickListener() {

			@Override
			public void onClick(View v) {
				//moveTaskToBack(true);
				
				finish();
				
				stopService(new Intent(getBaseContext(), UnderServ.class));
				startService(new Intent(getBaseContext(), UnderServ.class));
				
			}
		});
		
		cek_setting();
		
	} // akhir dari oncreate
	
	private void simpan_data(){
		
		try {
			
			 new upload_sync().execute();
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
		}
		
	} // akhir dari simpan data
	
	private void savelocal() throws Exception {
		
		Integer ceksms;
		Integer cektelp;
		//Integer jarak;
		//Integer waktu;
		
		//jarak=0;
		//waktu=0;
		
		String	sjenis= (String) cbjenis.getSelectedItem();
		
		//if (sjenis.equals("Jarak")){
		//	jarak = Integer.valueOf(tjarak.getText().toString());
		//}else{
		//	waktu = Integer.valueOf(tjarak.getText().toString());
		//}
		
		if (csms.isChecked()){
			ceksms =1;
		}else{ ceksms =1; }
		
		if (ctelp.isChecked()){
			cektelp =1;
		}else{ cektelp =1; }
		
		
		String sql="select * from utility";
		String sql_update ="update utility set namapemilik='"+ tpemilik.getText().toString().trim() +"',notelp='"+ ttelp.getText().toString().trim() +"',"
				+ "ceksms="+ ceksms +",cektelp="+ cektelp +",jrakref="+ Double.valueOf(tjarak.getText().toString()) +",jnismonitor='"+ sjenis +"'";
		String sql_insert="insert into utility (namapemilik,notelp,ceksms,cektelp,jrakref,jnismonitor) values("
				+ "'" + tpemilik.getText().toString().trim() +"','"+ ttelp.getText().toString().trim() +"',"+ ceksms +","+ cektelp 
				+ ","+ Double.valueOf(tjarak.getText().toString()) + ",'"+ sjenis + "')";
		
		db = dba.getReadableDatabase();
	       
	       Cursor cursor = dba.SelectData(db,sql);
	       cursor.moveToFirst(); 
	       int jml_baris = cursor.getCount();
		
	    if (jml_baris >= 1) {
	    	
	    	String namapemilik = cursor.getString(cursor.getColumnIndex("namapemilik"));
	    	
	    	if (namapemilik.trim().length() >=1 ){
	    		db.execSQL(sql_update);
	    	}else{
	    		db.execSQL(sql_insert);
	    	}
	    	
	    	
	    }else{
	    	db.execSQL(sql_insert);
	    }
		
	}
	
	private void cek_setting(){
		
		try {
			
			String sql_cekuser ="select _id,namapemilik,notelp,jrakref,ceksms,cektelp,jnismonitor from utility";
		       db = dba.getReadableDatabase();
		       
		       Cursor cursor = dba.SelectData(db,sql_cekuser);
		       cursor.moveToFirst(); 
		       int jml_baris = cursor.getCount();
		       
		       if (jml_baris >=1){
		    	   
		    	   tpemilik.setText(cursor.getString(cursor.getColumnIndex("namapemilik")));
		    	   ttelp.setText(cursor.getString(cursor.getColumnIndex("notelp")));
		    	   tjarak.setText(cursor.getString(cursor.getColumnIndex("jrakref")));
		    	   
		    	   String ceksms = cursor.getString(cursor.getColumnIndex("ceksms"));
		    	   String cektelp = cursor.getString(cursor.getColumnIndex("cektelp"));
		    	   
		    	   if (ceksms.equals("0")){
		    		   csms.setChecked(false);
		    	   }else{
		    		   csms.setChecked(true);
		    	   }
		    	   
		    	   if (cektelp.equals("0")){
		    		   ctelp.setChecked(false);
		    	   }else{
		    		   ctelp.setChecked(true);
		    	   }
		    	   
		    	   String sjenis = cursor.getString(cursor.getColumnIndex("jnismonitor"));
		    	   if (sjenis.equals("Jarak")){
		    		   cbjenis.setSelection(0);
		    	   }else{
		    		   cbjenis.setSelection(1);
		    	   }
		    	   
		       }else{
		    	   tpemilik.setText("");
		    	   ttelp.setText("");
		    	   tjarak.setText("0.0");
		    	   csms.setChecked(false);
		    	   ctelp.setChecked(false);
		       }
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
		}
		
	}// akhir cek setting
	
	@Override
	 protected Dialog onCreateDialog(int id) {
	 
	  AlertDialog dialogDetails = null;
	 
	  switch (id) {
	  case 1:
		  LayoutInflater inflater = LayoutInflater.from(this);
		  View dialogview = inflater.inflate(R.layout.lrubah_pwd, null);
		 
		   AlertDialog.Builder dialogbuilder = new AlertDialog.Builder(this);
		   dialogbuilder.setTitle("Rubah Password");	
		   dialogbuilder.setView(dialogview);
		   dialogDetails = dialogbuilder.create();
		   
		   break;
	  }
		   return dialogDetails;
		   
	  } // akhir oncreate dialog
	
	@Override
	 protected void onPrepareDialog(int id, final Dialog dialog) {
	 
	  switch (id) {
	  case 1:
		  final AlertDialog alertDialog2 = (AlertDialog) dialog;
		   Button cancelbutton2 = (Button) alertDialog2
				     .findViewById(R.id.btcancelpwd);
		   
		   Button okbutton2 = (Button) alertDialog2
				     .findViewById(R.id.btrubahpwd);
		  
		   final EditText tuser_rbh = (EditText)dialog.findViewById(R.id.tuser_rbh);
		   final EditText tpwd_rbh = (EditText)dialog.findViewById(R.id.tpwd_rbh1);
		   
		   tuser_rbh.setText("admin");
		  // tuser_rbh.setEnabled(false);
		   
		   tpwd_rbh.requestFocus();
		   
		   okbutton2.setOnClickListener(new View.OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					
					Integer isipwd = tpwd_rbh.getText().length();
					
					if (isipwd < 1) {
						Toast.makeText(getApplicationContext(), "Password harus diisi",Toast.LENGTH_SHORT).show();
						tpwd_rbh.requestFocus();
						return;
					}
					
					try {
						
						db = dba.getReadableDatabase();
						String sql="update user set pass='"+ tpwd_rbh.getText().toString() +"' where nama='"+ tuser_rbh.getText().toString() +"'";
						
						db.execSQL(sql);
						
						db.close();
						alertDialog2.dismiss();
						
						Toast.makeText(getApplicationContext(), "Password "+ tuser_rbh.getText().toString() +" telah dirubah..."  ,Toast.LENGTH_SHORT).show();
						
					} catch (Exception e) {
						// TODO: handle exception
						Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
						db.close();
					}
					
					tpwd_rbh.setText("");
					
					
					
				}});
		   
		   cancelbutton2.setOnClickListener(new View.OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					alertDialog2.dismiss();
				}});
		   
		  break;
		  
	  }}
	
	
	private void uploade_data()throws Exception{
		try {
			
			Integer ceksmst=0;
			Integer cektelpt=0;
			
			if (csms.isChecked()){
				ceksmst=1;
			}else{ceksmst=0;}
			
			if (ctelp.isChecked()){
				cektelpt=1;
			}else{cektelpt=0;}
			
			Double jarak;
			Double waktu;
			
			jarak=0.0;
			waktu=0.0;
			
			String	sjenis= (String) cbjenis.getSelectedItem();
			
			if (sjenis.equals("Jarak")){
				jarak = Double.valueOf(tjarak.getText().toString());
			}else{
				waktu = Double.valueOf(tjarak.getText().toString());
			}
			
			ArrayList<NameValuePair> postparam = new ArrayList<NameValuePair>();
			postparam.add(new BasicNameValuePair("nama", tpemilik.getText().toString().trim()));
			postparam.add(new BasicNameValuePair("telp", ttelp.getText().toString().trim()));
			postparam.add(new BasicNameValuePair("jarak", jarak.toString()));
			postparam.add(new BasicNameValuePair("waktu", waktu.toString()));
			postparam.add(new BasicNameValuePair("cektelp", cektelpt.toString()));
			postparam.add(new BasicNameValuePair("ceksms", ceksmst.toString()));
			postparam.add(new BasicNameValuePair("jnismonitor",sjenis ));
			
			String res = CustomHttpClient.executeHttpPost(url, postparam);
			 
			 if (res.toString().trim().equals("1")){
				 
				 jmlok++;	// Toast.makeText(getApplicationContext(), "Data berhasil dikirim",Toast.LENGTH_SHORT).show();
			 }else {
				 jmler++; // Toast.makeText(getApplicationContext(), "Data gagal dikirim",Toast.LENGTH_SHORT).show();
			 }
			
		} catch (Exception e) {
			// TODO: handle exception
			jmler++;
		}
	}
	
	
	
    private class upload_sync extends AsyncTask<Void,Void, String>{
    	
    	private ProgressDialog pDialog;
    	
    	@Override
        protected void onPreExecute() {
            super.onPreExecute();
            pDialog = new ProgressDialog(Csettings.this);
            pDialog.setMessage("Mohon sabar menunggu, Proses upload sedang berlangsung...");
            pDialog.setIndeterminate(false);
            pDialog.setCancelable(true);
            pDialog.show();
        }
    	
    	@Override
		protected void onPostExecute(String content) {
    		
    		pDialog.dismiss();
    		
            if (jmler > 0) {
            	Toast.makeText(Csettings.this,jmler + 
                        " Upload data gagal...", Toast.LENGTH_SHORT).show(); 
            } else {
            	Toast.makeText(Csettings.this, 
                       "Upload data selesai...", Toast.LENGTH_SHORT).show(); 
            	
            	try {
					savelocal();
				} catch (Exception e) {
					// TODO Auto-generated catch block
					//e.printStackTrace();
					Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
				}
            	
            	
            	
            }
            
            jmler=0;
            jmlok=0;
            
        }
    	
		@Override
		protected String doInBackground(Void... arg0) {
			// TODO Auto-generated method stub
			
			try {
				uploade_data();
			} catch (Exception e) {
				// TODO: handle exception
				Log.v("error upl noo", e.toString());
			}
			
			
			return null;
		}
    	
		
    } // akhir dari asyncTask
	
	
}

package wmi.appl.com;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.view.Menu;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

public class WmiActivity extends Activity {
	
	private DBAdapter dba=null;
	private SQLiteDatabase db = null; 
	private Cursor dbCursor = null;  
	
	TextView tuser;
	TextView tpwd;
	
	ImageButton btmasuk;
	ImageButton btkeluar;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_wmi);
		
		dba = new DBAdapter(this);
		
		tuser = (TextView)findViewById(R.id.ta_user);
		tpwd = (TextView)findViewById(R.id.ta_pwd);
		
		btmasuk = (ImageButton)findViewById(R.id.bta_masuk);
		btkeluar = (ImageButton)findViewById(R.id.bta_close);
		
		tuser.requestFocus();
		
		 String sql_cekuser ="select * from user";
	       db = dba.getReadableDatabase();
	       
	       Cursor cursor = dba.SelectData(db,sql_cekuser);
	       cursor.moveToFirst(); 
	       int jml_baris = cursor.getCount();
	       
	       if (jml_baris >=1){
	    	   
	      	 String usertabel = cursor.getString(cursor.getColumnIndex("nama"));
	      	 
	      	 if (usertabel.toString().length() <1) {
	      		 insert_useradmin();
	      	 }
	      	 
	         }else{
	      	   insert_useradmin();
	         }
	     
	       
	       btmasuk.setOnClickListener(new Button.OnClickListener() {
	        	
				@Override
				public void onClick(View v) {
					
					if (tuser.getText().length()==0){
						Toast.makeText(getApplicationContext(), "User harus diisi",Toast.LENGTH_SHORT).show();
						tuser.setFocusable(true);
						tuser.requestFocus();
						return;
					}
					
					if (tpwd.getText().length()==0){
						Toast.makeText(getApplicationContext(), "Password harus diisi",Toast.LENGTH_SHORT).show();
						tpwd.setFocusable(true);
						tpwd.requestFocus();
						return;
					}
					
					masuk_ok();
					
				}
				
				
	        });
	       
	       btkeluar.setOnClickListener(new Button.OnClickListener() {

				@Override
				public void onClick(View v) {
					moveTaskToBack(true);
				}
			});
	       
		
	} // akhir on create

	private void insert_useradmin(){
		db = dba.getWritableDatabase();
		
		try {
			
			String sql ="insert into user (nama,pass) values('admin','admin')";
			db.execSQL(sql);
			
		} catch (Exception e) {
			// TODO: handle exception
			Toast.makeText(getBaseContext(), e.toString(), Toast.LENGTH_LONG).show();
		}
		
		
	}
	
	private void masuk_ok(){
		
		db = dba.getReadableDatabase(); 
		
		String sql="select _id,nama from user where nama='"+ tuser.getText().toString().trim() +"' and pass='"+ tpwd.getText().toString().trim() +"'";
		
		dbCursor = dba.SelectData(db, sql);
		dbCursor.moveToFirst(); 
		
        int jml_baris = dbCursor.getCount();
        
        if(jml_baris == 0)  {
        	
        	dbCursor.close();
			Toast.makeText(getApplicationContext(), "User atau Password salah",Toast.LENGTH_SHORT).show();
			
			return;
        }else {
			
			Intent myIntent = new Intent(this, Csettings.class);
			
			dbCursor.close();
			
			startActivity(myIntent);
			
			finish();
			return;
        	
        }
        
	}
	
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_wmi, menu);
		return true;
	} // akhir on create option

	@Override
    protected void onResume(){
    	super.onResume();
    }
    
    @Override
    protected void onPause(){
    	super.onPause();
    }
    
    @Override
    protected void onDestroy(){
    	super.onDestroy();
    }	
	
}

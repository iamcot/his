/*
 * DicomFile.java - ファイルのオープン・ファイルの切り出し・VRの解析
 *
 * Copyright(C) 2000, Nagoya Institute of Technology, Iwata laboratory and Takahiro Katoji
 * http://mars.elcom.nitech.ac.jp/dicom/
 *
 * @author	Takahiro Katoji(mailto:katoco@mars.elcom.nitech.ac.jp)
 * @version
 *
 */

package dicomviewer;

import java.io.*;
import java.text.*;
import java.net.*;
import java.util.*;

public class DicomFile {

  int             debug_level = 3;

  boolean         isLtlEndian;      // littleEndian転送構文のとき、true
  boolean         vrType;           // 明示的VRの場合、true
  boolean         patientPrivacy;   // 患者のプライバシーを守るため、患者名を変換するときtrue
  boolean         containDic;       // DICOM辞書に含まれるタグか？
  DicomDic	      dicomDic;         // DICOM辞書
  DicomData       dicomData;        // 格納するデータの箱

  // コンストラクタ
  public DicomFile(boolean argIsLtlEndian, boolean argVRType, boolean privacy, DicomDic argDicomDic) {
    patientPrivacy = privacy;
    isLtlEndian = argIsLtlEndian;
    vrType = argVRType;
    dicomDic = argDicomDic;
  }
  public DicomFile(boolean argIsLtlEndian, boolean argVRType, DicomDic argDicomDic) {
    this(argIsLtlEndian, argVRType, false, argDicomDic);
  }
  public DicomFile(DicomDic argDicomDic) {
    this(true, false, false, argDicomDic);
  }

  // DICOMファイルより切り出す
  public DicomData load(String imgURL){
    // データを格納する箱を準備する
    dicomData = new DicomData();

    try {
      // DICOMファイルをhttpでゲットするためにURLを作る
      URL urlConn = new URL(imgURL);
      URLConnection connection = urlConn.openConnection();
      // InputStreamを作成する。
      // 一度、BufferedInputStreamをかますことにより高速化。
      // InputStream inS = urlConn.openStream();
      //BufferedInputStream inS = new BufferedInputStream(urlConn.openStream());

      int tempInt, metaTag;
      byte[] buff2 = new byte[2];
      byte[] buff4 = new byte[4];

      String group;
      String number;
      String tag;
      String vr;
      int length, metaLength;
      byte[] value;
      int bytes;
      int total = connection.getContentLength();
      DataInputStream din = new DataInputStream(connection.getInputStream());
      byte[] bulk = new byte[total];
      din.readFully(bulk);
      din.close();

      int index = 0;
      // check for Part 10 Header format
      StringBuffer signature = new StringBuffer(4);
      System.arraycopy(bulk, 128, buff4, 0, 4);
      for(int i=0; i<4; i++)
	    signature.append((char)buff4[i]);
      if (signature.toString().equals("DICM")) {
        index += 128 + 4;
        total -= 128 + 4;
        // parse the File Meta Information Group
        for(int i=0; i<3; i++) {
          System.arraycopy(bulk, index, buff4, 0, 4);
          index += 4;
          total -= 4;
        }
        length = readInt4(buff4);
		int metaTotal = length;
		while (metaTotal > 0) {
        	System.arraycopy(bulk, index, buff4, 0, 4);
        	index += 4;
        	metaTotal -= 4;
			metaTag = readInt4(buff4);
        	System.arraycopy(bulk, index, buff2, 0, 2);
        	index += 2;
        	metaTotal -= 2;
			String metaVr = new String(buff2);
          	if (metaVr.equals("OB") || metaVr.equals("OF") ||
             	metaVr.equals("OW") || metaVr.equals("UT") ||
             	metaVr.equals("UN") || metaVr.equals("SQ")) {
            	index += 2;
            	metaTotal -= 2;
            	System.arraycopy(bulk, index, buff4, 0, 4);
            	index += 4;
            	metaTotal -= 4;
            	metaLength = readInt4(buff4);
          	} else {
            	System.arraycopy(bulk, index, buff2, 0, 2);
            	index += 2;
            	metaTotal -= 2;
            	metaLength = readInt2(buff2);
          	}
            if (debug_level > 3) {
                System.out.println("File Meta Infor: tag = " + Integer.toHexString(metaTag));
                System.out.println("File Meta Infor: VR = " + metaVr);
                System.out.println("File Meta Infor: length = " + metaLength);
            }
        	// read the encoding transfer syntax
			if (metaTag == 0x100002) {
        		byte[] uid = new byte[metaLength];
        		System.arraycopy(bulk, index, uid, 0, metaLength);
				String xferSyntax = new String(uid);
				xferSyntax.trim();
				if (xferSyntax.indexOf("1.2.840.10008.1.2.1") != -1 ||
					xferSyntax.indexOf("1.2.840.10008.1.2.2") != -1)
					vrType = true;
			}
        	index += metaLength;
        	metaTotal -= metaLength;
		}
		total -= length;
      }
      // ファイルを最後まで読む
      while (total > 0) {
        System.arraycopy(bulk, index, buff2, 0, 2);
        index += 2;
        total -= 2;
        // タグ
        tempInt = readInt2(buff2);
        group  = Integer.toString((tempInt&0x0000f000)>>12,16);
        group += Integer.toString((tempInt&0x00000f00)>>8,16);
        group += Integer.toString((tempInt&0x000000f0)>>4,16);
        group += Integer.toString((tempInt&0x0000000f),16);

        System.arraycopy(bulk, index, buff2, 0, 2);
        index += 2;
        total -= 2;
        tempInt = readInt2(buff2);
        number  = Integer.toString((tempInt&0x0000f000)>>12,16);
        number += Integer.toString((tempInt&0x00000f00)>>8,16);
        number += Integer.toString((tempInt&0x000000f0)>>4,16);
        number += Integer.toString((tempInt&0x0000000f),16);
        tag = ("("+group+","+number+")");

        // デバッグ用
        if (debug_level > 3) System.out.println("currentTag is : " + tag);
        dicomData.setTag(tag);  // DicomDataにセット

        // DICOM辞書に含まれているかどうか？
        containDic = dicomDic.isContain(tag);

        int iGroup = Integer.parseInt(group, 16);
        if(vrType && (iGroup != 0xFFFE)){
				  // ファイルが明示的VRの場合
          StringBuffer sbuff = new StringBuffer(2);
          System.arraycopy(bulk, index, buff2, 0, 2);
          index += 2;
          total -= 2;
          for(int i=0; i<2; i++)
	          sbuff.append((char)buff2[i]);
          dicomData.setVR(tag, sbuff.toString());

          // VRによって、値長さが変わる。
          if(sbuff.toString().equals("OB") ||
             sbuff.toString().equals("OW") ||
             sbuff.toString().equals("OF") ||
             sbuff.toString().equals("UT") ||
             sbuff.toString().equals("UN") ||
             sbuff.toString().equals("SQ")) {
            // VRがOB、OW、またはSQの場合
            index += 2;
            total -= 2;
            // 値長さ(4bytes読み込むVersion)
            System.arraycopy(bulk, index, buff4, 0, 4);
            index += 4;
            total -= 4;
            length = readInt4(buff4);
          } else {
            // VRがOB、OW、またはSQ以外
				    // 値長さ(2bytes読み込むVersion)
            System.arraycopy(bulk, index, buff2, 0, 2);
            index += 2;
            total -= 2;
            length = readInt2(buff2);
          }
        } else{
      	  // ファイルが暗黙的VRの場合
				  // VRはDICOM辞書にてゲットする。
				  // 値長さ(4bytes読み込むVersion)
          if(containDic) dicomData.setVR(tag, dicomDic.getVR(tag));
          else dicomData.setVR(tag, "na");
          System.arraycopy(bulk, index, buff4, 0, 4);
          index += 4;
          total -= 4;
          length = readInt4(buff4);
        }

        vr = dicomData.getVR(tag);
        // デバッグ用
        if (debug_level > 3) System.out.println("currentVR is : " + vr);
        if (debug_level > 3) System.out.println("currentLength: " + length);

        //要素長さが未定義長さの場合
        if(length == -1) {
          length = 0;
        }

        // 値
        value = new byte[length];
        System.arraycopy(bulk, index, value, 0, length);
        index += length;
        total -= length;
        dicomData.setValue(tag, value);

        // データの取得
        if(containDic) {
          dicomData.setName(tag, dicomDic.getName(tag));
          dicomData.setVM(tag, dicomDic.getVM(tag));
          dicomData.setVersion(tag, dicomDic.getVersion(tag));
        }else {
          dicomData.setName(tag, "NotContainedInDICOMDictionary");
          dicomData.setVM(tag, "na");
          dicomData.setVersion(tag, "na");
        }

        // デバッグ用
        if (debug_level > 3) System.out.println("currentName is : " + dicomData.getName(tag));

        this.analyzer(tag, vr);

        // no need to parse Pixel Data
        long longTag = Long.parseLong(group+number, 16);
        if ((iGroup != 0xfffe) && (longTag > 0x7fe00000))
          break;

      } // while ここまで。

      //inS.close();
    }
    catch(EOFException eof){
      System.out.println("DicomFile.EOFException: " + eof.getMessage() );
    }
    catch(IOException ioe){
      System.out.println("DicomFile.IOException: " + ioe.getMessage() );
    }
    /*
    catch(Exception e){
      System.out.println("DicomFile.Exception: " + e.getMessage() );
    }
    */
    
    // プライバシー対策のコード
    // (0010,0010)のデータを
    //     Takahiro Katoji -> T*k*h*r* *a*o*i
    // のような「*」混じりの文字列に変換する
    if(patientPrivacy) {
      String patientName;
      // 現在DicomDataにセットされている患者名を取得する
      patientName = dicomData.getAnalyzedValue("(0010,0010)");
      StringBuffer patientBuf = new StringBuffer(patientName);
      
      // 患者名の奇数番目の文字を「*」に変換する
      for(int i=0; i < patientName.length(); i++) {
        if(i % 2 == 1) patientBuf.setCharAt(i, '*');
      }
      
      // 変換後の文字列をDicomDataに戻す
      dicomData.setAnalyzedValue("(0010,0010)", patientBuf.toString());
    }
    
    // DicomDataを返して終了
    return dicomData;
  }

  // 2bytes読んでIntに変換
  private int readInt2(byte[] argtmp){
    int tmp;
    if(isLtlEndian) {
      tmp = ((0x000000ff & argtmp[1]) << 8 | (0x000000ff & argtmp[0]));
    } else {
      tmp = ((0x000000ff & argtmp[0]) << 8 | (0x000000ff & argtmp[1]));
    }
    return tmp;
  }

  // 4bytes読んでIntに変換
  private int readInt4(byte[] argtmp){
    int tmp;
    if(isLtlEndian) {
      tmp = ((0x000000ff & argtmp[3]) << 24 | (0x000000ff & argtmp[2]) << 16
           | (0x000000ff & argtmp[1]) << 8  | (0x000000ff & argtmp[0]));
    } else {
      tmp = ((0x000000ff & argtmp[0]) << 24 | (0x000000ff & argtmp[1]) << 16
           | (0x000000ff & argtmp[2]) << 8  | (0x000000ff & argtmp[3]));
    }
    return tmp;
  }

  // VRを解析しデータ要素の値を適切な書式に変換する。
  private void analyzer(String currentTag, String currentVR) {
	
    if(currentVR==null){
      // VRが無い場合
      dicomData.setAnalyzedValue(currentTag, "Not contain VR.");
    }
    else if(dicomData.getValueLength(currentTag)==0){
      // 大きさ0は無視
      dicomData.setAnalyzedValue(currentTag, "");
    }
    else if(currentVR.equals("PN") | currentVR.equals("LO")
          |	currentVR.equals("SH") | currentVR.equals("LT")
          |	currentVR.equals("ST") | currentVR.equals("UI")
          |	currentVR.equals("DS") | currentVR.equals("CS")
          |	currentVR.equals("IS") | currentVR.equals("AS")){
      // 普通の文字列
      for(int j=0; j<dicomData.getValueLength(currentTag); j++)
        if((dicomData.getValue(currentTag))[j] == 0)
           (dicomData.getValue(currentTag))[j] = 20;
      dicomData.setAnalyzedValue(currentTag, new String(dicomData.getValue(currentTag)));

    }
    else if(currentVR.equals("SS")){
      int tmp;
      // 16bit符合付2進数
      if(isLtlEndian){
        tmp = (((int)(dicomData.getValue(currentTag))[1] & 0x000000ff) << 8)
             | ((int)(dicomData.getValue(currentTag))[0] & 0x000000ff);
      } else {
        tmp = (((int)(dicomData.getValue(currentTag))[0] & 0x000000ff) << 8)
             | ((int)(dicomData.getValue(currentTag))[1] & 0x000000ff);
      }
      if((tmp & 0x00008000)==0x00008000) 	// 符合処理
				  tmp |= 0xffff0000;
      dicomData.setAnalyzedValue(currentTag, Integer.toString(tmp));

    }
    else if(currentVR.equals("US")){
      int tmp;
      // 16bit符合無2進数
      if(isLtlEndian){
        tmp = (((int)(dicomData.getValue(currentTag))[1] & 0x000000ff) << 8)
             | ((int)(dicomData.getValue(currentTag))[0] & 0x000000ff);
      } else {
        tmp = (((int)(dicomData.getValue(currentTag))[0] & 0x000000ff) << 8)
             | ((int)(dicomData.getValue(currentTag))[1] & 0x000000ff);
      }
      dicomData.setAnalyzedValue(currentTag, Integer.toString(tmp));
    }
    else if(currentVR.equals("UL")){
      int tmp;
      // 32bit符合無2進数
      if (isLtlEndian){
        tmp = (((int)(dicomData.getValue(currentTag))[3] & 0x000000ff) << 24)
            | (((int)(dicomData.getValue(currentTag))[2] & 0x000000ff) << 16)
            | (((int)(dicomData.getValue(currentTag))[1] & 0x000000ff) <<  8)
            |  ((int)(dicomData.getValue(currentTag))[0] & 0x000000ff);
      } else {
        tmp = (((int)(dicomData.getValue(currentTag))[0] & 0x000000ff) << 24)
            | (((int)(dicomData.getValue(currentTag))[1] & 0x000000ff) << 16)
            | (((int)(dicomData.getValue(currentTag))[2] & 0x000000ff) <<  8)
            |  ((int)(dicomData.getValue(currentTag))[3] & 0x000000ff);
      }
      dicomData.setAnalyzedValue(currentTag, Integer.toString(tmp));

    }
    else if(currentVR.equals("TM")){
      // 時間 hh:mm:ss.frac
      dicomData.setAnalyzedValue(currentTag, new String(dicomData.getValue(currentTag)));
      StringBuffer buffer = new StringBuffer(dicomData.getAnalyzedValue(currentTag));
      buffer.insert(2, ":");
      buffer.insert(5, ":");
      dicomData.setAnalyzedValue(currentTag, buffer.toString());
    }
    else if(currentVR.equals("DA")){
      // 日付 yyyy.mm.dd
      dicomData.setAnalyzedValue(currentTag, new String(dicomData.getValue(currentTag)));

      // 8bytesしかないときは,「-」を追加する
      if(dicomData.getValueLength(currentTag)==8){
        StringBuffer buffer = new StringBuffer(dicomData.getAnalyzedValue(currentTag));
        buffer.insert(4, "-");
        buffer.insert(7, "-");
        dicomData.setAnalyzedValue(currentTag, buffer.toString());
      }else if(dicomData.getValueLength(currentTag) == 10){
        // 10bytesのときは,「.」「-」に変更する
        StringTokenizer st = new StringTokenizer(dicomData.getAnalyzedValue(currentTag), ".");
        String temp  = st.nextToken();
        temp += "-" + st.nextToken();
        temp += "-" + st.nextToken();
        dicomData.setAnalyzedValue(currentTag, temp);
      }
    }
    else
      // サポートしていないタグ
      dicomData.setAnalyzedValue(currentTag, "Unknown VR");
    // デバッグ用
    if (debug_level > 3) System.out.println("AnalyzedValue :" + dicomData.getAnalyzedValue(currentTag));
  }
}



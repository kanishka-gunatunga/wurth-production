<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $payment->id }}</title>
    @php
    $fontPath1 = str_replace('\\', '/', public_path('fonts/WuerthBook.ttf'));
    @endphp
    @php
    $fontPath2 = str_replace('\\', '/', public_path('fonts/WuerthGlobal-Book.ttf'));
    @endphp
    @php
    $fontPath3 = str_replace('\\', '/', public_path('fonts/WuerthGlobal-ExtraBoldCond_V2_2.ttf'));
    @endphp
    <style>   

    body {
        font-family: 'WuerthBook', sans-serif;
        margin: 0;
        padding: 0;
    }

    @font-face {
        font-family: 'WuerthBook';
        /* src: url('{{ asset('fonts/WuerthBook.ttf') }}') format('truetype'); */
        src: url("file:///{{ $fontPath1 }}") format('truetype');


    }

    @font-face {
        font-family: 'WuerthGlobalBook';
        /* src: url('{{ asset('fonts/WuerthGlobal-Book.ttf') }}') format('truetype'); */
        src: url("file:///{{ $fontPath2 }}") format('truetype');
   
    }

    @font-face {
        font-family: 'WuerthGlobalExtra';
        /* src: url('{{ asset('fonts/WuerthGlobal-ExtraBoldCond_V2_2.ttf') }}') format('truetype'); */
       src: url("file:///{{ $fontPath3 }}") format('truetype');

    }
        .header { text-align: center; margin-bottom: 20px; }
        .customer-info, .invoice-table, .signature { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        .signature img { max-width: 200px; max-height: 100px; } 
         h1, h2, h3, p { margin: 0; padding: 0; }
        .header1{
            font-size: 32pt;
            font-weight:800;
            font-family: 'WuerthBook', sans-serif;
        }
        .header2{
            font-size: 28pt;
            font-weight:800;
            font-family: 'WuerthGlobalExtra', sans-serif;
        }
        .header3{
            font-size: 30pt;
            font-weight:400;
             font-family: 'WuerthBook', sans-serif;
        }
        
        .text{
            font-size: 16pt;
             font-family: 'WuerthBook', sans-serif;
        }
        .text2{
             font-family: 'WuerthGlobalExtra', sans-serif;
            font-size: 17pt;
        }
        .text-bold{
            font-weight:800;
        }
        .footer {
            
        }
    </style>
</head>
<body>
    <table style="width:100%; border-collapse: collapse; border: none;">
    <tbody>
        <tr style="vertical-align: middle;">
            <td style="width:65%; text-align:center; border:none;">
                <h1 class="header1" style="margin-bottom:10px;">Wurth Lanka (Private) Limited</h1>
                <p class="text text-bold">
                    No. 375/B, High Level Road, Makumbura, Pannipitiya.<br>
                    Tel: 0112 894 975 Fax: 0112 894 955
                </p>
            </td>
            <td style="width:35%; text-align:right; border:none; vertical-align: middle;">
                <?php if($is_duplicate == 1){ ?>
                    <p class="text text-bold" style="margin-bottom:20px;">Customer Copy</p>
                <?php } ?>
                <img src="{{ public_path('assets/images/wruth-logo.png') }}" style="width:90%;">
            </td>
        </tr>
    </tbody>
</table>

<table style="width:100%; border-collapse: collapse; border: none;margin-top:20px;">
    <tbody>
        <tr>
            <td style="width:50%; text-align:center; border:none;">
                <h1 class="header2" style="text-decoration:underline; text-align:right;">
                    Official Receipt
                </h1>
            </td>
            <td style="width:50%; text-align:right; border:none;">
                <table style="border:none; margin-left:auto;">
                    <tr style="vertical-align: middle;">
                        <td style="border:none; padding-left:25px;padding-right:5px;width:35%;">
                            <p class="text text-bold">Receipt No :</p>
                        </td>
                        <td style="border:none;width:65%;">
                            <h1 class="header3" style="text-align:left;">{{ $payment->id }}</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<table style="width:100%; border-collapse: collapse; border: none;margin-top:20px;">
    <tbody>
        <tr style="vertical-align: middle;">
            <td style="width:50%; text-align:left; border:none;">
                <p class="text">
                    <span class="text-bold">Date</span> <span style="border-bottom:1px dotted black;margin-left:5px;"> {{ \Carbon\Carbon::parse($payment->created_at)->format('Y-m-d') }}</span>
                </p>
            </td>
            <td style="width:50%; text-align:left; border:none; vertical-align: middle;">
                <p class="text"> <span class="text-bold">Time</span>  <span style="border-bottom:1px dotted black;margin-left:5px;">{{ \Carbon\Carbon::parse($payment->created_at)->format('H:i') }}</span></p>
            </td>
        </tr>

          <tr style="vertical-align: middle;">
              <td style="width:100%; text-align:left; border:none;" colspan="2">
                  <p class="text">
                      <span class="text-bold">Received with thanks from</span>
                      <span style="border-bottom:1px dotted black;margin-left:5px;">
                          {{  $payment->invoice->customer->name ?? '-' }}
                      </span>
                  </p>
              </td>
          </tr>
          <tr style="vertical-align: middle;">
              <td style="width:100%; text-align:left; border:none;" colspan="2">
                  <p class="text">
                      <span class="text-bold">Customer No</span>
                      <span style="border-bottom:1px dotted black;margin-left:5px;">
                          {{  $payment->invoice->customer->customer_id ?? '-' }}
                      </span>
                  </p>
              </td>
          </tr>

    </tbody>

</table>

<table style="width:100%; border-collapse: collapse; border: none;margin-top:20px;">
  <tbody>
    <tr style="vertical-align: top;">
      <!-- Payment details -->
      <td style="width:47%; text-align:left; border:none;">
        <table style="width:100%; border-collapse: collapse; border: none;">
          <tbody>
            <tr>
              <td style="width:50%; border:none;">
                <p class="text">
                    @if($payment->type == 'cash')
                        Cash Amount
                    @elseif($payment->type == 'fund-transfer')
                        Fund Transfer Amount
                    @elseif($payment->type == 'card')
                        Card Payment Amount
                    @elseif($payment->type == 'cheque')
                        Cheque Amount
                    @else
                        Amount
                    @endif
                </p>
              </td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  LKR {{ number_format($payment->final_payment, 2) }}
                </p>
              </td>
            </tr>
        
            @if($payment->type == 'cheque')
            <tr>
              <td style="width:50%; border:none;"><p class="text">Cheque No</p></td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  {{ $payment->cheque_number ?? '-' }}
                </p>
              </td>
            </tr>
            <tr>
              <td style="width:50%; border:none;"><p class="text">Deposit Date</p></td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  {{ $payment->cheque_number ?? '-' }}
                </p>
              </td>
            </tr>
            <tr>
              <td style="width:50%; border:none;"><p class="text">Bank & Branch</p></td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  {{ ($payment->type == 'cheque') ? ($payment->bank_name . ', ' . $payment->branch_name) : '-' }}
                </p>
              </td>
            </tr>
            @endif
             @if($payment->type == 'fund-transfer')
             <tr>
              <td style="width:50%; border:none;"><p class="text">Transfer Date</p></td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  {{ $payment->transfer_date ?? '-' }}
                </p>
              </td>
            </tr>
            <tr>
              <td style="width:50%; border:none;"><p class="text">Bank</p></td>
              <td style="width:50%; border:none;">
                <p class="text" style="padding:3px;border:1px solid;min-height:30px;">
                  {{ $payment->bank_name ?? '-' }}
                </p>
              </td>
            </tr>
            @endif
          </tbody>
        </table>

      </td>

      <!-- Spacer -->
      <td style="width:6%; text-align:left; border:none; vertical-align: top;"></td>

      <!-- Invoice details -->
      <td style="width:47%; text-align:left; border:none; vertical-align: top;">
        <p class="text" style="text-align:center">Invoice #</p>
        <div style="width:100%; padding:5px; border:1px solid #000; box-sizing:border-box;">
  <table style="width:100%; border-collapse: collapse; border: none;">
    <tbody>
     
      <tr>
        <td style="width:50%; border:none; border-bottom:1px dotted black;">
          <p class="text">{{ $payment->invoice->invoice_or_cheque_no }}</p>
        </td>
        <!--<td style="width:40%; border:none; border-bottom:1px dotted black;">-->
        <!--  <p class="text"></p>-->
        <!--</td>-->
        <td style="width:50%; border:none; border-bottom:1px dotted black;">
          <p class="text">LKR {{ number_format($payment->invoice->amount, 2) }}</p>
        </td>
      </tr>
      
    </tbody>
  </table>
</div>

      </td>
    </tr>
  </tbody>
</table>


<table style="width:100%; border-collapse: collapse; border: none; margin-top:20px;">
    <tbody>
        <tr style="vertical-align: middle;">
            <td style="width:100%; text-align:left; border:none;">
                <p class="text" style="width:100%; margin:0; padding:0;">
                    <span class="text-bold">Customer Signature</span>  
                    <span style="border-bottom:1px dotted black; display:inline-block; vertical-align:bottom; margin-left:5px;">
                        @if(!empty($batch->customer_signature) && file_exists(public_path($batch->customer_signature)))
                            <img
                                src="{{ public_path($batch->customer_signature) }}"
                                alt="Customer Signature"
                                style="width:250px; display:block; margin:0; padding:0;"
                            >
                        @else
                            <span style="display:inline-block; width:250px; height:80px; border-bottom:1px dotted #000;"></span>
                        @endif
                    </span>
                </p>
            </td>
        </tr>
        <tr style="vertical-align: middle;">
            <td style="width:100%; text-align:left; border:none;">
                <p class="text" style="width:100%; margin:0; padding:0;">
                    <span class="text-bold">Collected By (Name)</span>  
                    <span style="border-bottom:1px dotted black; margin-left:5px;">{{ $payment->adm->userDetails->name  ?? '-' }}</span>
                </p>
            </td>
        </tr>
    </tbody>
</table>



<div class="footer" style="margin-top:50px;">
    
    @if($payment->type == 'Cheque')
        <p class="text2 text-bold" style="margin:0;text-align:center;">"This receipt is valid subject to realization of the cheque"</p>
    @endif
    

    <hr style="width:100%; height:2px; background-color:#000; border:none; margin:5px 0;">

     <p class="text" style="text-align:center;margin-top:10px;">This is a system-generated receipt issued automatically by our system. No signature is required.</p>
   
      <?php if($is_temp == 1){ ?>
    <p class="text" style="text-align:center; margin-top:10px; font-style:italic; color:#555;">
        This is a temporary receipt. The official receipt will be issued and sent to you promptly upon completion of your signature.
    </p>

      <?php } ?>             
</div>

  
</body>
</html>

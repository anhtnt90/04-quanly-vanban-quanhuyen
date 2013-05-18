--Exec eCS_RecordGetAll 'F5E44548-2583-4104-8531-5A866F6A2DAE','KHONG_LIEN_THONG','0974D77A-BF46-487F-B492-DA2E93E45C93','','THU_LY,TRA_LAI','','THULY_CHINH','order by C_RECEIVED_DATE desc','TXSC','','1','15'
ALTER PROCEDURE dbo.eCS_RecordGetAll
	@sRecordTypeId					nvarchar(50) -- Id loai danh muc ho so TTHC
	,@sRecordType					Varchar(30)  -- Loai ho so TTHC: LIEN_THONG, KHONG_LIEN_THONG
	,@iCurrentStaffId				Varchar(50)	 -- Id can bo dang nhap hien thoi
	,@sReceiveDate					Datetime	 -- Ngay tiep nhan
	,@sStatusList					Varchar(1000)-- Danh sach trang thai giai quyet ho so
	,@sDetailStatusCompare			Varchar(500) -- Chuoi dieu kien lay du lieu theo tung truong hop cu the
	,@sRole							Varchar(50)	 -- Nhom quyen NSD
	,@sOrderClause					Varchar(300) -- Chuoi mo ta menh de sap xep du lieu
	,@sOwnerCode					Varchar(20)	 -- Ma don vi su dung
	,@sfulltextsearch				Nvarchar(200)-- Tu, cum tu tim kiem
	,@iPage							Int			 -- Trang hien thoi
	,@iNumberRecordPerPage			Int			 -- So ban ghi tren trang
AS
	Declare @v_str_sql nvarchar(4000), @pTotalRecord int,@dApoidDate datetime
	SET NOCOUNT ON
	Create Table #T_STATUS_LIST(PK_STATUS_LIST Varchar(100))
	Exec Sp_ListToTable @sStatusList, 'PK_STATUS_LIST', '#T_STATUS_LIST', ','	
	-- Tao bang temp
	Create Table #T_ALL_RECORD( PK_RECORD Varchar(50)
								,C_CODE Varchar(30)
								,FK_RECORDTYPE Varchar(50)
								,C_RECEIVED_DATE Datetime
								,C_APPOINTED_DATE Datetime
								,C_RECEIVED_RECORD_XML_DATA xml
								,C_REASON nvarchar(200)
								,C_OWNER_CODE varchar(20)
								,C_SUBMIT_ORDER_DATE	Datetime
								,C_SUBMIT_ORDER_CONTENT nvarchar(200)
								,C_FILE_NAME nvarchar(300)
								,PK_RECORD_TRANSITION Varchar(50)
								,C_CURRENT_STATUS Varchar(50)
								,C_DETAIL_STATUS int
								,C_TAX_APPOINTED_DATE datetime
								,C_TREASURY_APPOINTED_DATE datetime
	)
	Set @v_str_sql = ' Insert into #T_ALL_RECORD '
	Set @v_str_sql = @v_str_sql + ' Select PK_RECORD,C_CODE,FK_RECORDTYPE,C_RECEIVED_DATE,C_APPOINTED_DATE
										   ,C_RECEIVED_RECORD_XML_DATA,C_REASON,C_OWNER_CODE,C_SUBMIT_ORDER_DATE,C_SUBMIT_ORDER_CONTENT,C_FILE_NAME,'+char(39)+char(39)+',C_CURRENT_STATUS,C_DETAIL_STATUS,C_TAX_APPOINTED_DATE,C_TREASURY_APPOINTED_DATE'
	Set @v_str_sql = @v_str_sql + ' From T_eCS_RECORD A Where 1=1 '
	-- Loc theo cac tieu chi tim kiem
	If @sRecordTypeId<>'' And @sRecordTypeId Is Not Null
		Set @v_str_sql = @v_str_sql + ' And FK_RECORDTYPE = ' + char(39) + @sRecordTypeId + char(39)	 
	If @sReceiveDate Is Not Null And @sReceiveDate <> ''
		Set @v_str_sql = @v_str_sql + ' And datediff(d,C_RECEIVED_DATE,' + char(39)+ Convert(varchar,@sReceiveDate)+ char(39)+')=0'	
	If @sStatusList Is Not Null And @sStatusList <> ''
		Set @v_str_sql = @v_str_sql + ' And (C_CURRENT_STATUS In (Select PK_STATUS_LIST From #T_STATUS_LIST)) '
	If @sOwnerCode Is Not Null And @sOwnerCode <> ''
		Set @v_str_sql = @v_str_sql + ' And C_OWNER_CODE = ' + char(39) + @sOwnerCode + char(39)
	If @sRole <> '' And @sRole is not null
		Begin
			Set @v_str_sql = @v_str_sql + 
			case @sRole
				When @sRole Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + @sRole + CHAR(39) +' And FK_STAFF = ' + char(39)+ @iCurrentStaffId+ char(39) + ')'
				--When 'THULY_CHINH' Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + 'THULY_CHINH' + CHAR(39) +' And FK_STAFF = ' + convert(varchar(10),@iCurrentStaffId) + ')'
				--When 'DUYET_CAP_MOT' Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_MOT' + CHAR(39) +' And FK_STAFF = ' + convert(varchar(10),@iCurrentStaffId) + ')'
				--When 'DUYET_CAP_HAI' Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_HAI' + CHAR(39) +' And FK_STAFF = ' + convert(varchar(10),@iCurrentStaffId) + ')'
				--When 'DUYET_CAP_BA' Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_BA' + CHAR(39) +' And FK_STAFF = ' + convert(varchar(10),@iCurrentStaffId) + ')'
				--When 'THUE' Then ' And PK_RECORD In (Select FK_RECORD From T_eCS_RECORD_RELATE_STAFF Where C_ROLES = ' + CHAR(39) + 'THUE' + CHAR(39) +' And FK_STAFF = ' + convert(varchar(10),@iCurrentStaffId) + ')'
			End
		End
	If @sfulltextsearch <> '' And @sfulltextsearch is not null
		Set @v_str_sql = @v_str_sql + ' And convert(nvarchar(max),C_DATA_TEMP.query(' + char(39) + '/root/data_list/*/text()' + char(39) + ')) like' + char(39) + '%' + dbo.Lower2Upper(@sfulltextsearch) + '%' + char(39)
	-- Chuoi mo ta dieu kien
	If @sDetailStatusCompare <> '' And @sDetailStatusCompare Is Not Null 
		Set @v_str_sql = @v_str_sql + ' ' + @sDetailStatusCompare
	If @sOrderClause<>'' And @sOrderClause Is Not Null 
		Set @v_str_sql = @v_str_sql + ' ' + @sOrderClause
	PRINT @v_str_sql
	Exec (@v_str_sql)
    If @sRecordType = 'LIEN_THONG'
		Begin
			-- Lay FK_PARENT_RECORDTYPE cua THHC lien thong ung vs @sRecordTypeId ID TTHC truyen vao
			Select @sRecordTypeId = FK_PARENT_RECORDTYPE From T_eCS_RECORDTYPE_TRANSITION Where FK_RECORDTYPE = @sRecordTypeId And C_OWNER_CODE = @sOwnerCode
			Set @v_str_sql = ' Insert into #T_ALL_RECORD '
			Set @v_str_sql = @v_str_sql + ' Select PK_RECORD,B.C_CODE,FK_RECORDTYPE,A.C_RECEIVED_DATE,A.C_APPOINTED_DATE
												   ,B.C_RECEIVED_RECORD_XML_DATA,A.C_REASON,A.C_OWNER_CODE,A.C_SUBMIT_ORDER_DATE,A.C_SUBMIT_ORDER_CONTENT,A.C_FILE_NAME,PK_RECORD_TRANSITION,B.C_CURRENT_STATUS,B.C_DETAIL_STATUS,B.C_TAX_APPOINTED_DATE,B.C_TREASURY_APPOINTED_DATE' 
			Set @v_str_sql = @v_str_sql + ' From T_eCS_RECORD_TRANSITION A,T_eCS_RECORD B  Where PK_RECORD=FK_RECORD '
			-- Loc theo cac tieu chi tim kiem
			If @sRecordTypeId<>'' And @sRecordTypeId Is Not Null
				Set @v_str_sql = @v_str_sql + ' And FK_RECORDTYPE = ' + char(39) + @sRecordTypeId + char(39)	 
			If @sReceiveDate Is Not Null And @sReceiveDate <> ''
				Set @v_str_sql = @v_str_sql + ' And datediff(d,A.C_RECEIVED_DATE,' + char(39)+ Convert(varchar,@sReceiveDate)+ char(39)+')=0'	
			If @sStatusList Is Not Null And @sStatusList <> ''
				Set @v_str_sql = @v_str_sql + ' And (A.C_CURRENT_STATUS In (Select PK_STATUS_LIST From #T_STATUS_LIST)) '
			If @sOwnerCode Is Not Null And @sOwnerCode <> ''
				Set @v_str_sql = @v_str_sql + ' And A.C_OWNER_CODE = ' + char(39) + @sOwnerCode + char(39)
			If @sRole <> '' And @sRole is not null
				Begin
					Set @v_str_sql = @v_str_sql + 
					case @sRole
						When 'THULY_CHINH' Then ' And A.FK_RECORD In (Select C.FK_RECORD From T_eCS_RECORD_TRANSITION C Inner Join T_eCS_RECORD_TRANSITION_RELATE_STAFF On PK_RECORD_TRANSITION = FK_RECORD_TRANSITION Inner Join T_eCS_RECORD_RELATE_STAFF On FK_RECORD_RELATE_STAFF = PK_RECORD_RELATE_STAFF   Where C_ROLES = ' + CHAR(39) + 'THULY_CHINH' + CHAR(39) + ' And FK_STAFF = ' + char(39)+ @iCurrentStaffId+ char(39) + ')'
						When 'DUYET_CAP_MOT' Then ' And A.FK_RECORD In (Select C.FK_RECORD From T_eCS_RECORD_TRANSITION C Inner Join T_eCS_RECORD_TRANSITION_RELATE_STAFF On PK_RECORD_TRANSITION = FK_RECORD_TRANSITION Inner Join T_eCS_RECORD_RELATE_STAFF On FK_RECORD_RELATE_STAFF = PK_RECORD_RELATE_STAFF   Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_MOT' + CHAR(39) + ' And FK_STAFF = ' +  char(39)+ @iCurrentStaffId+ char(39) + ')'
						When 'DUYET_CAP_HAI' Then ' And A.FK_RECORD In (Select C.FK_RECORD From T_eCS_RECORD_TRANSITION C Inner Join T_eCS_RECORD_TRANSITION_RELATE_STAFF On PK_RECORD_TRANSITION = FK_RECORD_TRANSITION Inner Join T_eCS_RECORD_RELATE_STAFF On FK_RECORD_RELATE_STAFF = PK_RECORD_RELATE_STAFF   Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_HAI' + CHAR(39) + ' And FK_STAFF = ' +  char(39)+ @iCurrentStaffId+ char(39) + ')'
						When 'DUYET_CAP_BA' Then ' And A.FK_RECORD In (Select C.FK_RECORD From T_eCS_RECORD_TRANSITION C Inner Join T_eCS_RECORD_TRANSITION_RELATE_STAFF On PK_RECORD_TRANSITION = FK_RECORD_TRANSITION Inner Join T_eCS_RECORD_RELATE_STAFF On FK_RECORD_RELATE_STAFF = PK_RECORD_RELATE_STAFF   Where C_ROLES = ' + CHAR(39) + 'DUYET_CAP_BA' + CHAR(39) + ' And FK_STAFF = ' +  char(39)+ @iCurrentStaffId+ char(39) + ')'
					End
				End
			If @sfulltextsearch <> '' And @sfulltextsearch is not null
				Set @v_str_sql = @v_str_sql + ' And convert(nvarchar(max),A.C_DATA_TEMP.query(' + char(39) + '/root/data_list/*/text()' + char(39) + ')) like' + char(39) + '%' + dbo.Lower2Upper(@sfulltextsearch) + '%' + char(39)
			-- Chuoi mo ta dieu kien
			If @sDetailStatusCompare <> '' And @sDetailStatusCompare Is Not Null 
				Set @v_str_sql = @v_str_sql + ' ' + @sDetailStatusCompare
			If @sOrderClause<>'' And @sOrderClause Is Not Null 
				Set @v_str_sql = @v_str_sql + ' ' + @sOrderClause
			print @v_str_sql
			Exec (@v_str_sql)
		End
    -- Temp table contain sorted data
	Create Table #T_ALL_SORTED_RECORD(  P_ID int IDENTITY (1,1)
										,PK_RECORD Varchar(50)
										,C_CODE Varchar(30)
										,FK_RECORDTYPE Varchar(50)
										,C_RECEIVED_DATE Datetime
										,C_APPOINTED_DATE Datetime
										,C_RECEIVED_RECORD_XML_DATA xml
										,C_REASON nvarchar(200)
										,C_OWNER_CODE varchar(20)
										,C_SUBMIT_ORDER_DATE	Datetime
										,C_SUBMIT_ORDER_CONTENT nvarchar(200)
										,C_FILE_NAME nvarchar(300)
										,PK_RECORD_TRANSITION Varchar(50)
										,C_CURRENT_STATUS varchar(50)
										,C_DETAIL_STATUS int
										,C_TAX_APPOINTED_DATE datetime
										,C_TREASURY_APPOINTED_DATE datetime
	)
	Insert Into #T_ALL_SORTED_RECORD
	Select * From #T_ALL_RECORD
	Order By C_RECEIVED_DATE  Desc
	--Get all data limit by @iPage and @iNumberRecordPerPage
	--select @dApoidDate=C_APPOINTED_DATE from T_eCS_RECORD_RELATE_UNIT where FK_RECORD=PK_RECORD
	Select @pTotalRecord = count(*)  From #T_ALL_SORTED_RECORD
	Select PK_RECORD
			,C_CODE
			,FK_RECORDTYPE
			,convert(varchar(10),C_RECEIVED_DATE,103) + ' ' + convert(varchar(5),C_RECEIVED_DATE,108) As C_RECEIVED_DATE
			,convert(varchar(10),C_APPOINTED_DATE,103) As C_APPOINTED_DATE
			,C_RECEIVED_RECORD_XML_DATA
			,C_REASON
			,C_OWNER_CODE
			,PK_RECORD_TRANSITION
			,@pTotalRecord as C_TOTAL_RECORD 
			,case @sRole
				when 'THULY_CHINH' then 
								case @sRecordType
									when 'LIEN_THONG' then dbo.f_GetOutofDateofRecord(getdate(),
											case (select count(*) from T_eCS_RECORD_TRANSITION where FK_RECORD=#T_ALL_SORTED_RECORD.PK_RECORD)
												when 0 then C_APPOINTED_DATE
												else (select C_TRANSITION_APPOINTED_DATE from T_eCS_RECORD_TRANSITION where FK_RECORD=#T_ALL_SORTED_RECORD.PK_RECORD)
												end )
									else	dbo.f_GetOutofDateofRecord(getdate(),dbo.f_GetAppointedDate(PK_RECORD,@iCurrentStaffId)) end						
				else		
						case C_CURRENT_STATUS when 'CHUYEN_TIEP' 
							then case C_DETAIL_STATUS when '22' then dbo.f_GetOutofDateofRecord(getdate(),C_TAX_APPOINTED_DATE)
														when '23' then dbo.f_GetOutofDateofRecord(getdate(),C_TREASURY_APPOINTED_DATE)
														end
							else dbo.f_GetOutofDateofRecord(getdate(),C_APPOINTED_DATE) 
							end 
				end As OUT_OF_DATE
			,C_SUBMIT_ORDER_DATE
			,C_SUBMIT_ORDER_CONTENT
			,C_FILE_NAME
			,dbo.f_GetTextStatus(C_CURRENT_STATUS,C_DETAIL_STATUS) As C_TEXT_STATUS
			,case C_CURRENT_STATUS when 'TRA_LAI' then '<font color="#FF0000">'+C_SUBMIT_ORDER_CONTENT+'</font>'
									when 'THU_LY' then dbo.f_GetAssignedIdea(PK_RECORD,@iCurrentStaffId,@sOwnerCode)+' ('+convert(varchar(10),dbo.f_GetAssignedDate(PK_RECORD,@iCurrentStaffId,@sOwnerCode),103)+')'
									when 'CHUYEN_TIEP' then 
														case (select C_CURRENT_STATUS from T_eCS_RECORD_TRANSITION where FK_RECORD=#T_ALL_SORTED_RECORD.PK_RECORD)
															when 'THU_LY' then dbo.f_GetAssignedIdea(PK_RECORD,@iCurrentStaffId,@sOwnerCode)+' ('+convert(varchar(10),dbo.f_GetAssignedDate(PK_RECORD,@iCurrentStaffId,@sOwnerCode),103)+')'
															when 'TRA_LAI' then '<font color="#FF0000">'+(select C_SUBMIT_ORDER_CONTENT from T_eCS_RECORD_TRANSITION where FK_RECORD=#T_ALL_SORTED_RECORD.PK_RECORD)+'</font>'
															else C_SUBMIT_ORDER_CONTENT
														end
									else C_SUBMIT_ORDER_CONTENT end as C_MESSAGE
			From #T_ALL_SORTED_RECORD			
 			Where  P_ID >((@iPage - 1) * @iNumberRecordPerPage) and P_ID <= (@iPage * @iNumberRecordPerPage)
	SET NOCOUNT OFF
Return 0
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetAppointedDate]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetAppointedDate]
GO
CREATE function f_GetAppointedDate(@sRecordId Varchar(50),@sStaffId Varchar(50))
	Returns datetime
	As
		Begin
			Declare @sUnitId Varchar(50),@dAppointedDate datetime
			select 	@sUnitId=FK_UNIT from DBLink.[sys-user-txsongcong].dbo.T_USER_STAFF where PK_STAFF=@sStaffId
			select @dAppointedDate=C_APPOINTED_DATE from T_eCS_RECORD_RELATE_UNIT where FK_RECORD=@sRecordId and FK_UNIT=@sUnitId
			if @dAppointedDate is null
				select @dAppointedDate=C_APPOINTED_DATE from T_eCS_RECORD where PK_RECORD=@sRecordId
			Return @dAppointedDate
		End
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetAssignedIdea]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetAssignedIdea]
GO
CREATE function f_GetAssignedIdea(@sRecordId Varchar(50),@sStaffId Varchar(50),@sOwnerCode Varchar(20))
	Returns nvarchar(200)
	As
		Begin
			Declare @sUnitId Varchar(50),@sAssigned nvarchar(200)
			select 	@sUnitId=FK_UNIT from DBLink.[sys-user-txsongcong].dbo.T_USER_STAFF where PK_STAFF=@sStaffId
			select @sAssigned=C_ASSIGNED_IDEA from T_eCS_RECORD_RELATE_UNIT where FK_RECORD=@sRecordId and FK_UNIT=@sUnitId	
			if @sAssigned is null	
				select @sAssigned= C_RESULT from T_eCS_RECORD_WORK where FK_RECORD=@sRecordId and (C_WORKTYPE='BAN_GIAO' or C_WORKTYPE='PHAN_CONG') and C_OWNER_CODE=@sOwnerCode
			Return @sAssigned
		End
GO
if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetAssignedDate]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetAssignedDate]
GO
CREATE function f_GetAssignedDate(@sRecordId Varchar(50),@sStaffId Varchar(50),@sOwnerCode Varchar(20))
	Returns datetime
	As
		Begin
			Declare @sUnitId Varchar(50),@dAssignedDate datetime
			select 	@sUnitId=FK_UNIT from DBLink.[sys-user-txsongcong].dbo.T_USER_STAFF where PK_STAFF=@sStaffId
			select @dAssignedDate=C_ASSIGNED_DATE from T_eCS_RECORD_RELATE_UNIT where FK_RECORD=@sRecordId and FK_UNIT=@sUnitId
			--nau can bo tiep nhan chuyen cho can bo thu ly khong qua lanh dao
			if @dAssignedDate is null
				select @dAssignedDate= C_WORK_DATE from T_eCS_RECORD_WORK where FK_RECORD=@sRecordId and (C_WORKTYPE='BAN_GIAO' or C_WORKTYPE='PHAN_CONG') and C_OWNER_CODE=@sOwnerCode
			Return @dAssignedDate
		End
GO
--select FK_UNIT from DBLink.[sys-user-txsongcong].dbo.T_USER_STAFF

ALTER PROCEDURE dbo.eCS_RecordTypeGetAllByStaff
	@iCurrentStaffId				Varchar(50)			-- Id can bo dang nhap hien thoi
	,@sOwnerCode					Varchar(20)			-- Ma don vi su dung
	,@sClauseString					Nvarchar(1000) = ''	-- Menh de dieu kien
AS
	Declare @v_str_sql nvarchar(4000)
	SET NOCOUNT ON
		Set @v_str_sql = 'Select distinct	PK_RECORDTYPE
									,C_CODE
									,C_NAME
									,C_RECORD_TYPE
									,C_PROCESS_NUMBER_DATE
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'TIEP_NHAN' + char(39) + ') AS C_RECEIVER_ID_LIST
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'THU_LY' + char(39) + ') AS C_HANDLER_ID_LIST
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'THUE' + char(39) + ') AS C_TAX_ID_LIST
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'KHO_BAC' + char(39) + ') AS C_TREASURY_ID_LIST
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'DUYET_ID' + char(39) + ') AS C_APPROVE_LEADER_ID_LIST
									,dbo.f_GetInfoByRecordTypeId(PK_RECORDTYPE,' + char(39) + 'DUYET_QUYEN' + char(39) + ') AS C_ROLES_CODE_LIST
									,C_COST_NEW
						  From T_eCS_RECORDTYPE A, T_eCS_RECORDTYPE_RELATE_STAFF B
						  Where PK_RECORDTYPE = FK_RECORDTYPE And C_OWNER_CODE = ' + char(39) + @sOwnerCode + char(39) + ' And FK_STAFF = ' + Char(39) + @iCurrentStaffId + char(39) 
		If @sClauseString <> '' And @sClauseString is not null
			Set @v_str_sql = @v_str_sql + @sClauseString
		Exec(@v_str_sql)
	SET NOCOUNT OFF
Return 0
GO

if exists (select * from dbo.sysobjects where id = object_id(N'[dbo].[f_GetInfoByRecordTypeId]') and xtype in (N'FN', N'IF', N'TF'))
drop function [dbo].[f_GetInfoByRecordTypeId]
GO

CREATE function [dbo].[f_GetInfoByRecordTypeId](@sRecordTypeId Varchar(50), @sType Varchar(50))
Returns Varchar(1500)
WITH ENCRYPTION
As
	Begin
		Declare @valuelist Varchar(1000)
		set @valuelist = ''
		If @sType = 'TIEP_NHAN'
			Select @valuelist = @valuelist + convert(varchar(50),FK_STAFF) + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and C_ROLES = @sType
		Else If @sType = 'THU_LY'
			Select @valuelist = @valuelist + convert(varchar(50),FK_STAFF) + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and C_ROLES = 'THU_LY' 
		Else If @sType = 'THUE'
			Select @valuelist = @valuelist + convert(varchar(50),FK_STAFF) + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and C_ROLES = 'THUE' 
		Else If @sType = 'KHO_BAC'
			Select @valuelist = @valuelist + convert(varchar(50),FK_STAFF) + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and C_ROLES = 'KHO_BAC' 
		Else If @sType = 'DUYET_ID'
			Select @valuelist = @valuelist + convert(varchar(50),FK_STAFF) + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and (C_ROLES = 'DUYET_CAP_MOT' Or C_ROLES = 'DUYET_CAP_HAI' Or C_ROLES = 'DUYET_CAP_BA') 
		Else If @sType = 'DUYET_QUYEN'
			Select @valuelist = @valuelist + C_ROLES + ','
			From T_eCS_RECORDTYPE_RELATE_STAFF
			Where FK_RECORDTYPE = @sRecordTypeId and (C_ROLES = 'DUYET_CAP_MOT' Or C_ROLES = 'DUYET_CAP_HAI' Or C_ROLES = 'DUYET_CAP_BA') 
		if @valuelist <> ''
			set @valuelist = substring(@valuelist,1,len(@valuelist)-1)
		Return @valuelist
	End
GO
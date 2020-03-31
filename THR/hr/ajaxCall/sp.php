USE [MOENational]
GO
/****** Object:  StoredProcedure [dbo].[SP_TG_GetClassOfGrade]    Script Date: 6/9/2014 12:40:07 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- =============================================
-- Author:	Duminda Wijewantha
-- Create date: 09-06-2014
-- Description:	Get Classed for grade
-- Create for Tekgeeks
-- =============================================
ALTER PROCEDURE [dbo].[SP_TG_GetClassOfGrade] 
	-- Add the parameters for the stored procedure here
	@LOGGEDUSERID NCHAR(10)	,
	@ACCESSLEVEL INT,
	@GradeID INT,
	@SchoolID nvarchar(20)		
	
AS
BEGIN
	DECLARE @SQLString nvarchar(max);
	DECLARE @ParmDefinition nvarchar(500);
	DECLARE @SC NCHAR(2);

	SET @SC = 'SC';

	SELECT @SQLString = 'SELECT ID,ClassID
  FROM TG_SchoolClassStructure
				WHERE (CN.InstType = N'''+@SC+''')'

	

	IF @SchoolID IS NOT NULL
	SELECT @SQLString = @SQLString + ' AND (SchoolID = @xSchoolID)' 

	IF @GradeID IS NOT NULL
	SELECT @SQLString = @SQLString + ' AND (GradeID= @xGradeID)'

	

	SELECT @SQLString = @SQLString + ' ORDER BY ClassID'

	SET @ParmDefinition = N'@xSchoolID nvarchar(20),
							@xGradeID int,';


    EXECUTE sp_executesql @SQLString, @ParmDefinition, @SchoolID, @GradeID;
	
END


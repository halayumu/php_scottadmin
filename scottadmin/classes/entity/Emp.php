<?php

/**
 * PH34 課題2 マスタテーブル管理  *
 * @author Ayumu ISHIDA
 *
 * ファイル名=emp.php
 * フォルダ=/ph34/ScottAdmin/classes/entity/ 
 */

/**
 *部門エンティティクラス。
 */
class Emp
{
    /**
     *  従業員ID
     */
    private ?int $id = null;
    /**
     * 従業員番号
     */
    private ?int $emNo = null;
    /**
     * 従業員名
     */
    private ?string $emName = "";
    /**
     * 役職
     */
    private ?string $emJob = "";
    /**
     * 上司番号
     */
    private ?int $emMgr = null;
    /**
     * 雇用日
     */
    private ?string $emHiredate = null;
    /**
     * 給料
     */
    private ?int $emSal = null;
    /**
     * 所属部門ID
     */
    private ?int $deptId = null;

    /**
     * 以下アクセサメソッド
     */
    public function setId(int $id): void //従業員ID
    {
        $this->id = $id;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setemNo(int $emNo): void //従業員番号
    {
        $this->emNo = $emNo;
    }
    public function getemNo(): ?int
    {
        return $this->emNo;
    }

    public function setemName(string $emName): void //従業員名
    {
        $this->emName = $emName;
    }
    public function getemName(): ?string
    {
        return $this->emName;
    }

    public function setemJob(?string $emJob): void //役職
    {
        $this->emJob = $emJob;
    }
    public function getemJob(): ?string
    {
        return $this->emJob;
    }

    public function setemMgr(?int $emMgr): void //上司番号
    {
        $this->emMgr = $emMgr;
    }
    public function getemMgr(): ?int
    {
        return $this->emMgr;
    }

    public function setemHiredate(?string $emHiredate): void //雇用日
    {
        $this->emHiredate = $emHiredate;
    }
    public function getemHiredate(): ?string
    {
        return $this->emHiredate;
    }

    public function setemSal(int $emSal): void //給料
    {
        $this->emSal = $emSal;
    }
    public function getemSal(): ?int
    {
        return $this->emSal;
    }

    public function setdeptId(int $deptId): void //所属部門ID
    {
        $this->deptId = $deptId;
    }
    public function getdeptId(): ?int
    {
        return $this->deptId;
    }
}

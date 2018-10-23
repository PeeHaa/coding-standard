<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\PHPStan;

use PHP_CodeSniffer\Files\File;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayTypeBuilder;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

class GetTokenDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{

	/** @var \PHPStan\Type\ArrayType|null */
	private $arrayType;

	public function getClass(): string
	{
		return File::class;
	}

	public function isMethodSupported(MethodReflection $methodReflection): bool
	{
		return $methodReflection->getName() === 'getTokens';
	}

	private function getTokensArrayType(): ArrayType
	{
		if ($this->arrayType === null) {
			$stringType = new StringType();
			$integerType = new IntegerType();
			$stringIntegerUnion = new UnionType([$stringType, $integerType]);
			$arrayBuilder = ConstantArrayTypeBuilder::createEmpty();
			$arrayBuilder->setOffsetValueType(new ConstantStringType('content'), $stringType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('code'), $stringIntegerUnion);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('type'), $stringType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('line'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('column'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('length'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('level'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('conditions'), new ArrayType($integerType, $stringIntegerUnion));
			$arrayBuilder->setOffsetValueType(new ConstantStringType('parenthesis_opener'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('parenthesis_closer'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('parenthesis_owner'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('scope_condition'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('scope_opener'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('scope_closer'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('nested_parenthesis'), new ArrayType($integerType, $integerType));
			$arrayBuilder->setOffsetValueType(new ConstantStringType('bracket_opener'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('bracket_closer'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('comment_opener'), $integerType);
			$arrayBuilder->setOffsetValueType(new ConstantStringType('comment_closer'), $integerType);

			$this->arrayType = new ArrayType($integerType, $arrayBuilder->getArray());
		}

		return $this->arrayType;
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter
	 * @param \PHPStan\Reflection\MethodReflection $methodReflection
	 * @param \PhpParser\Node\Expr\MethodCall $methodCall
	 * @param \PHPStan\Analyser\Scope $scope
	 * @return \PHPStan\Type\Type
	 */
	public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): Type
	{
		return $this->getTokensArrayType();
	}

}
